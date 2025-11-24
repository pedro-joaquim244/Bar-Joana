const reviews = document.querySelectorAll(".review");
const prev = document.querySelector(".prev");
const next = document.querySelector(".next");
const dotsContainer = document.querySelector(".dots");

let index = 0;

// Criar bolinhas dinamicamente
reviews.forEach((_, i) => {
    const dot = document.createElement("div");
    dot.classList.add("dot");
    if (i === 0) dot.classList.add("active");
    dot.addEventListener("click", () => goTo(i));
    dotsContainer.appendChild(dot);
});

const dots = document.querySelectorAll(".dot");

function updateCarousel() {
    reviews.forEach(r => r.classList.remove("active"));
    dots.forEach(d => d.classList.remove("active"));

    reviews[index].classList.add("active");
    dots[index].classList.add("active");
}

function goTo(i) {
    index = i;
    updateCarousel();
}

next.addEventListener("click", () => {
    index = (index + 1) % reviews.length;
    updateCarousel();
});

prev.addEventListener("click", () => {
    index = (index - 1 + reviews.length) % reviews.length;
    updateCarousel();
});

// Auto-play
setInterval(() => {
    index = (index + 1) % reviews.length;
    updateCarousel();
}, 5000);
