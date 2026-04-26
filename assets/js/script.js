document.addEventListener('DOMContentLoaded', function () {
  const scrollContainer = document.getElementById('kategoriScroll');
  const dots = document.querySelectorAll('.carousel-dots .dot');

  scrollContainer.addEventListener('scroll', () => {
    const scrollLeft = scrollContainer.scrollLeft;
    const containerWidth = scrollContainer.offsetWidth;

    const activeIndex = Math.round(scrollLeft / containerWidth);

    dots.forEach((dot, index) => {
      dot.classList.toggle('active', index === activeIndex);
    });
  });
});
