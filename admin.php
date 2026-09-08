<?php
require_once 'database/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Access control – only admins
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}

$pdo = getConnection();
$message = '';

// Handle admin actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['admin_action'] ?? '';

    // --- ADD NEW PRODUCT ---
    if ($action === 'add_product') {
        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $image = trim($_POST['image_path'] ?? '');
        $stock = (int)($_POST['stock'] ?? 0);
        
        $small_price = (float)($_POST['small_price'] ?? 0);
        $regular_price = (float)($_POST['regular_price'] ?? 0);
        $large_price = (float)($_POST['large_price'] ?? 0);

        if ($name && $desc && $small_price > 0 && $regular_price > 0 && $large_price > 0) {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image_path, stock) VALUES (:name, :desc, :price, :image, :stock)");
            $stmt->execute([
                'name' => $name,
                'desc' => $desc,
                'price' => $regular_price,
                'image' => $image,
                'stock' => $stock
            ]);
            $product_id = $pdo->lastInsertId();

            $sizes = [
                ['Small', $small_price],
                ['Regular', $regular_price],
                ['Large', $large_price]
            ];
            $stmt = $pdo->prepare("INSERT INTO product_sizes (product_id, size_name, price) VALUES (:pid, :size, :price)");
            foreach ($sizes as $size) {
                $stmt->execute(['pid' => $product_id, 'size' => $size[0], 'price' => $size[1]]);
            }
            $message = "Product added successfully!";
        } else {
            $message = "Please fill in all required fields (including all three prices).";
        }
    }

    // --- UPDATE ALL PRODUCTS ---
    elseif ($action === 'update_all') {
        // FIXED: Now $_POST['product_id'] will correctly be an array
        $product_ids = $_POST['product_id'] ?? [];
        
        if (!empty($product_ids) && is_array($product_ids)) {
            $stmt = $pdo->prepare("UPDATE products SET name = :name, description = :desc, image_path = :image, stock = :stock WHERE id = :id");
            $sizeStmt = $pdo->prepare("UPDATE product_sizes SET price = :price WHERE product_id = :pid AND size_name = :size");
            
            foreach ($product_ids as $pid) {
                $pid = (int)$pid;
                $name = trim($_POST['name'][$pid] ?? '');
                $desc = trim($_POST['description'][$pid] ?? '');
                $image = trim($_POST['image_path'][$pid] ?? '');
                $stock = (int)($_POST['stock'][$pid] ?? 0);
                if ($stock < 0) $stock = 0;

                $small_price = (float)($_POST['small_price'][$pid] ?? 0);
                $regular_price = (float)($_POST['regular_price'][$pid] ?? 0);
                $large_price = (float)($_POST['large_price'][$pid] ?? 0);

                $stmt->execute(['name' => $name, 'desc' => $desc, 'image' => $image, 'stock' => $stock, 'id' => $pid]);

                $sizes = [
                    ['Small', $small_price],
                    ['Regular', $regular_price],
                    ['Large', $large_price]
                ];
                foreach ($sizes as $size) {
                    $sizeStmt->execute(['price' => $size[1], 'pid' => $pid, 'size' => $size[0]]);
                }
            }
            $message = "All products updated successfully!";
        } else {
            $message = "No products to update.";
        }
    }

    // --- UPDATE INDIVIDUAL PRODUCT ---
    elseif ($action === 'update_individual') {
        $pid = (int)($_POST['update_id'] ?? 0);
        if ($pid > 0) {
            $name = trim($_POST['name'][$pid] ?? '');
            $desc = trim($_POST['description'][$pid] ?? '');
            $image = trim($_POST['image_path'][$pid] ?? '');
            $stock = (int)($_POST['stock'][$pid] ?? 0);
            if ($stock < 0) $stock = 0;

            $small_price = (float)($_POST['small_price'][$pid] ?? 0);
            $regular_price = (float)($_POST['regular_price'][$pid] ?? 0);
            $large_price = (float)($_POST['large_price'][$pid] ?? 0);

            $stmt = $pdo->prepare("UPDATE products SET name = :name, description = :desc, image_path = :image, stock = :stock WHERE id = :id");
            $stmt->execute(['name' => $name, 'desc' => $desc, 'image' => $image, 'stock' => $stock, 'id' => $pid]);

            $sizeStmt = $pdo->prepare("UPDATE product_sizes SET price = :price WHERE product_id = :pid AND size_name = :size");
            $sizes = [
                ['Small', $small_price],
                ['Regular', $regular_price],
                ['Large', $large_price]
            ];
            foreach ($sizes as $size) {
                $sizeStmt->execute(['price' => $size[1], 'pid' => $pid, 'size' => $size[0]]);
            }
            $message = "Product #$pid updated successfully!";
        }
    }

    // --- DELETE PRODUCT ---
    elseif ($action === 'delete_product') {
        // FIXED: Use the renamed input
        $product_id = (int)($_POST['delete_product_id'] ?? 0);
        if ($product_id > 0) {
            $pdo->prepare("DELETE FROM product_sizes WHERE product_id = :id")->execute(['id' => $product_id]);
            $pdo->prepare("DELETE FROM products WHERE id = :id")->execute(['id' => $product_id]);
            $message = "Product deleted.";
        }
    }
}

// Fetch all products
$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Build size lookup array
$sizesLookup = [];
if (!empty($products)) {
    $ids = array_column($products, 'id');
    $in = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT product_id, size_name, price FROM product_sizes WHERE product_id IN ($in)");
    $stmt->execute($ids);
    $sizesRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($sizesRows as $row) {
        $sizesLookup[$row['product_id']][$row['size_name']] = $row['price'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Fuafe</title>
    <link rel="stylesheet" href="styles.css?v=<?php echo filemtime('styles.css'); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>

   <section class="admin-section">
        <div class="container">
            <h1 class="page-title">Admin Dashboard</h1>

            <p style="margin-bottom:20px;"><a href="Comments/admin_comments.php" style="color: orange; font-weight: bold;">Manage Comments →</a></p>

            <?php if ($message): ?>
                <p class="admin-message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <!-- Add Product Form -->
            <div class="admin-panel">
                <h2>Add New Product</h2>
                <form method="POST" class="admin-form admin-form-grid">
                    <input type="hidden" name="admin_action" value="add_product">
                    <div class="form-column">
                        <label>Product Name</label>
                        <input type="text" name="name" required>
                        <label>Description</label>
                        <textarea name="description" required></textarea>
                        <label>Image Path</label>
                        <input type="text" name="image_path" placeholder="images/your-image.jpg" required>
                    </div>
                    <div class="form-column">
                        <label>Price (Small) ($)</label>
                        <input type="number" step="0.01" name="small_price" required>
                        <label>Price (Regular) ($)</label>
                        <input type="number" step="0.01" name="regular_price" required>
                        <label>Price (Large) ($)</label>
                        <input type="number" step="0.01" name="large_price" required>
                        <label>Initial Stock</label>
                        <input type="number" name="stock" value="0" required>
                    </div>
                    <button type="submit" class="btn-auth" style="grid-column: 1 / -1;">Add Product</button>
                </form>
            </div>

            <!-- Manage Inventory -->
            <div class="admin-panel">
                <h2>Manage Inventory</h2>
                <?php if (empty($products)): ?>
                    <p>No products found.</p>
                <?php else: ?>
                    
                      <!-- ONE FORM FOR ALL -->
                    <form method="POST" id="inventoryForm">
                        <!-- Hidden fields to be manipulated by JavaScript -->
                        <input type="hidden" name="admin_action" id="admin_action" value="update_all">
                        <input type="hidden" name="update_id" id="update_id" value="">
                        <input type="hidden" name="delete_product_id" id="delete_product_id" value="">
                        
                        <div class="table-scroll-wrapper">
                            <table class="admin-table">
                                <tr>
                                    <th>ID</th>
                                    <th>Image Path</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Small Price</th>
                                    <th>Regular Price</th>
                                    <th>Large Price</th>
                                    <th>Stock</th>
                                    <th>Actions</th>
                                </tr>
                                <?php foreach ($products as $product): ?>
                                    <?php 
                                        $small = $sizesLookup[$product['id']]['Small'] ?? 0;
                                        $regular = $sizesLookup[$product['id']]['Regular'] ?? 0;
                                        $large = $sizesLookup[$product['id']]['Large'] ?? 0;
                                    ?>
                                    <tr>
                                        <td><?= $product['id'] ?></td>
                                        <td><input type="text" name="image_path[<?= $product['id'] ?>]" value="<?= htmlspecialchars($product['image_path']) ?>" style="width:150px;"></td>
                                        <td><input type="text" name="name[<?= $product['id'] ?>]" value="<?= htmlspecialchars($product['name']) ?>" style="width:100px;"></td>
                                        <td><textarea name="description[<?= $product['id'] ?>]" style="width:200px; height:50px;"><?= htmlspecialchars($product['description']) ?></textarea></td>
                                        <td><input type="number" step="0.01" name="small_price[<?= $product['id'] ?>]" value="<?= $small ?>" style="width:70px;"></td>
                                        <td><input type="number" step="0.01" name="regular_price[<?= $product['id'] ?>]" value="<?= $regular ?>" style="width:70px;"></td>
                                        <td><input type="number" step="0.01" name="large_price[<?= $product['id'] ?>]" value="<?= $large ?>" style="width:70px;"></td>
                                        <td>
                                            <input type="number" name="stock[<?= $product['id'] ?>]" value="<?= (int)$product['stock'] ?>" min="0" style="width:60px;">
                                            <input type="hidden" name="product_id[]" value="<?= $product['id'] ?>">
                                        </td>
                                        <td>
                                            <!-- Buttons: Don't submit directly; use JavaScript -->
                                            <button type="button" class="btn-small" onclick="submitIndividual(<?= $product['id'] ?>)">Update</button>
                                            <button type="button" class="btn-danger" onclick="submitDelete(<?= $product['id'] ?>)">Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        
                        <!-- The main submit button: stays as "Update All" -->
                        <button type="submit" class="btn-auth" style="margin-top:20px;">Update All Products</button>
                    </form>

                <?php endif; ?>
            </div>
        </div>
    </section>

    <script>
        function submitIndividual(productId) {
            document.getElementById('admin_action').value = 'update_individual';
            document.getElementById('update_id').value = productId;
            document.getElementById('delete_product_id').value = '';
            document.getElementById('inventoryForm').submit();
        }

        function submitDelete(productId) {
            if (confirm('Delete this product?')) {
                document.getElementById('admin_action').value = 'delete_product';
                document.getElementById('delete_product_id').value = productId;
                document.getElementById('update_id').value = '';
                document.getElementById('inventoryForm').submit();
            }
        }
    </script>

    <?php include 'auth_footer.php'; ?>
</body>
</html>