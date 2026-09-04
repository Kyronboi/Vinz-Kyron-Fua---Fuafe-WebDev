document.addEventListener('DOMContentLoaded', function() {
    // Get all nav links
    const navLinks = document.querySelectorAll('.nav-links a');

    // 1. HOVER EFFECT: Turn orange on mouseenter, back to normal on mouseleave
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.classList.add('hover-orange');
        });
        link.addEventListener('mouseleave', function() {
            this.classList.remove('hover-orange');
        });
    });

    // 2. CLICK ACTIONS: Scroll or open blank page based on data-target
    navLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Stop default anchor jump

            const target = this.getAttribute('data-target');

            if (target === 'home') {
                // Scroll to top (hero section)
                document.getElementById('home').scrollIntoView({ behavior: 'smooth' });
            }
            else if (target === 'contact') {
                // Scroll to contact section
                document.getElementById('contact').scrollIntoView({ behavior: 'smooth' });
            }
            else if (target === 'blog') {
                // Scroll to comments/testimonials section
                document.getElementById('blog').scrollIntoView({ behavior: 'smooth' });
            }
            else if (target === 'menu') {
                // Open a blank page (placeholder)
                window.open('', '_blank'); // Opens a new empty tab
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const track = document.querySelector('.carousel-track');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const cards = document.querySelectorAll('.menu-card');

    if (!track || !prevBtn || !nextBtn) return;

    let currentIndex = 0;
    const totalCards = cards.length; // 4
    const visibleCards = 3; // Only 3 visible at a time

    // Calculate max index (how far we can slide)
    const maxIndex = totalCards - visibleCards; // 1

    function updateCarousel() {
        // Get width of one card and gap
        const cardWidth = cards[0].offsetWidth;
        const gap = 20; // Must match the gap in CSS
        const moveAmount = cardWidth + gap;

        // Apply the slide transformation
        track.style.transform = `translateX(-${currentIndex * moveAmount}px)`;
    }

    // Next Button (Click -> 4th appears, 1st disappears)
    nextBtn.addEventListener('click', () => {
        if (currentIndex >= maxIndex) {
            currentIndex = 0; // Loop back to start
        } else {
            currentIndex++;
        }
        updateCarousel();
    });

    // Previous Button (Click -> 1st appears, 4th disappears)
    prevBtn.addEventListener('click', () => {
        if (currentIndex <= 0) {
            currentIndex = maxIndex; // Loop to end
        } else {
            currentIndex--;
        }
        updateCarousel();
    });

    // Adjust on window resize
    window.addEventListener('resize', updateCarousel);

    // Initial position
    updateCarousel();
});
