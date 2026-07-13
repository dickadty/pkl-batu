document.addEventListener('DOMContentLoaded', () => {
    const heroSlider = document.getElementById('hero-slider');

    if (!heroSlider) {
        return;
    }

    const slides = Array.from(document.querySelectorAll('[data-hero-slide]'));
    const contents = Array.from(document.querySelectorAll('[data-hero-content]'));
    const dots = Array.from(document.querySelectorAll('[data-hero-dot]'));
    const nextButton = document.getElementById('hero-next');

    if (!slides.length) {
        return;
    }

    let activeIndex = 0;
    let intervalId;

    const showSlide = (index) => {
        activeIndex = (index + slides.length) % slides.length;

        slides.forEach((slide, slideIndex) => {
            const isActive = slideIndex === activeIndex;
            slide.classList.toggle('opacity-100', isActive);
            slide.classList.toggle('opacity-0', !isActive);
            slide.classList.toggle('z-10', isActive);
            slide.classList.toggle('z-0', !isActive);
        });

        contents.forEach((content, contentIndex) => {
            const isActive = contentIndex === activeIndex;
            content.classList.toggle('translate-y-0', isActive);
            content.classList.toggle('translate-y-8', !isActive);
            content.classList.toggle('opacity-100', isActive);
            content.classList.toggle('opacity-0', !isActive);
            content.classList.toggle('pointer-events-auto', isActive);
            content.classList.toggle('pointer-events-none', !isActive);
        });

        dots.forEach((dot, dotIndex) => {
            dot.classList.toggle('bg-yellow-400', dotIndex === activeIndex);
            dot.classList.toggle('bg-white/70', dotIndex !== activeIndex);
        });
    };

    const startSlider = () => {
        clearInterval(intervalId);
        intervalId = setInterval(() => {
            showSlide(activeIndex + 1);
        }, 8000);
    };

    dots.forEach((dot) => {
        dot.addEventListener('click', () => {
            showSlide(Number(dot.getAttribute('data-hero-dot')));
            startSlider();
        });
    });

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            showSlide(activeIndex + 1);
            startSlider();
        });
    }

    showSlide(activeIndex);
    startSlider();
});
