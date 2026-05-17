/* =====================================================
   IMAGE CAROUSEL
===================================================== */

document.addEventListener('DOMContentLoaded', () => {
  const carousel = {
    currentIndex: 0,
    slides: document.querySelectorAll('.carousel-slide'),
    totalSlides: document.querySelectorAll('.carousel-slide').length,
    
    init() {
      this.createDots();
      this.attachEventListeners();
      this.updateCarousel();
    },
    
    createDots() {
      const dotsContainer = document.getElementById('carouselDots');
      for (let i = 0; i < this.totalSlides; i++) {
        const dot = document.createElement('button');
        dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
        dot.addEventListener('click', () => this.goToSlide(i));
        dotsContainer.appendChild(dot);
      }
    },
    
    attachEventListeners() {
      document.getElementById('carouselPrev').addEventListener('click', () => this.prevSlide());
      document.getElementById('carouselNext').addEventListener('click', () => this.nextSlide());
      
      // Keyboard navigation
      document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') this.prevSlide();
        if (e.key === 'ArrowRight') this.nextSlide();
      });
    },
    
    nextSlide() {
      this.currentIndex = (this.currentIndex + 1) % this.totalSlides;
      this.updateCarousel();
    },
    
    prevSlide() {
      this.currentIndex = (this.currentIndex - 1 + this.totalSlides) % this.totalSlides;
      this.updateCarousel();
    },
    
    goToSlide(index) {
      this.currentIndex = index;
      this.updateCarousel();
    },
    
    updateCarousel() {
      // Update slides
      this.slides.forEach((slide, index) => {
        slide.classList.toggle('active', index === this.currentIndex);
      });
      
      // Update dots
      const dots = document.querySelectorAll('.carousel-dot');
      dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === this.currentIndex);
      });
    }
  };
  
  carousel.init();
  
  // Auto-rotate carousel every 5 seconds (optional)
  // setInterval(() => carousel.nextSlide(), 5000);
});
