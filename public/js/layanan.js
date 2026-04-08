// Layanan Slider
document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('layananGrid');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const dotsContainer = document.getElementById('layananDots');
    
    if (!grid || !prevBtn || !nextBtn) return;

    const items = grid.querySelectorAll('.layanan-item');
    const itemWidth = items[0].offsetWidth + 20;
    const visibleItems = Math.floor(grid.offsetWidth / itemWidth);
    const totalDots = Math.ceil(items.length / visibleItems);
    
    let currentIndex = 0;

    // Create dots
    for (let i = 0; i < totalDots; i++) {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if (i === 0) dot.classList.add('active');
        dot.addEventListener('click', () => goToSlide(i));
        dotsContainer.appendChild(dot);
    }

    const dots = dotsContainer.querySelectorAll('.dot');

    function updateDots() {
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }

    function goToSlide(index) {
        currentIndex = index;
        const scrollAmount = currentIndex * itemWidth * visibleItems;
        grid.scrollTo({
            left: scrollAmount,
            behavior: 'smooth'
        });
        updateDots();
    }

    prevBtn.addEventListener('click', function() {
        if (currentIndex > 0) {
            currentIndex--;
            goToSlide(currentIndex);
        }
    });

    nextBtn.addEventListener('click', function() {
        if (currentIndex < totalDots - 1) {
            currentIndex++;
            goToSlide(currentIndex);
        }
    });

    // Auto-update dots on scroll
    grid.addEventListener('scroll', function() {
        const scrollPosition = grid.scrollLeft;
        const maxScroll = grid.scrollWidth - grid.clientWidth;
        const scrollPercent = scrollPosition / maxScroll;
        const newIndex = Math.min(
            Math.round(scrollPercent * (totalDots -1)),
            totalDots - 1
        );
        if (newIndex !== currentIndex) {
            currentIndex = newIndex;
            updateDots();
        }
    });

    console.log('Layanan slider initialized!');
});