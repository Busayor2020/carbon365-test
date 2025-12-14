const track = document.querySelector(".hc-track");
const slides = Array.from(document.querySelectorAll(".hc-slide"));
const dots = Array.from(document.querySelectorAll(".hc-dot"));
const prevBtn = document.querySelector(".hc-prev");
const nextBtn = document.querySelector(".hc-next");

let currentIndex = 0;
let autoplayInterval;

function updateSlide(index) {
  currentIndex = (index + slides.length) % slides.length;
  track.style.transform = `translateX(-${currentIndex * 100}%)`;

  dots.forEach((dot, i) => {
    dot.setAttribute("aria-selected", i === currentIndex);
  });
}

function nextSlide() {
  updateSlide(currentIndex + 1);
}

function prevSlide() {
  updateSlide(currentIndex - 1);
}

function startAutoplay() {
  stopAutoplay();
  autoplayInterval = setInterval(nextSlide, 3000);
}

function stopAutoplay() {
  if (autoplayInterval) {
    clearInterval(autoplayInterval);
  }
}

prevBtn.addEventListener("click", () => {
  prevSlide();
  startAutoplay();
});

nextBtn.addEventListener("click", () => {
  nextSlide();
  startAutoplay();
});

dots.forEach((dot, index) => {
  dot.addEventListener("click", () => {
    updateSlide(index);
    startAutoplay();
  });
});

document
  .querySelector(".hc-viewport")
  .addEventListener("mouseenter", stopAutoplay);
document
  .querySelector(".hc-viewport")
  .addEventListener("mouseleave", startAutoplay);

startAutoplay();
