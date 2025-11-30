const track = document.querySelector(".review-track");
const reviews = document.querySelectorAll(".review");
const prev = document.querySelector(".prev");
const next = document.querySelector(".next");
const dotsContainer = document.querySelector(".dots");

let index = 0;

// Criar bolinhas
reviews.forEach((_, i) => {
    const dot = document.createElement("div");
    dot.classList.add("dot");
    if (i === 0) dot.classList.add("active");
    dot.addEventListener("click", () => goTo(i));
    dotsContainer.appendChild(dot);
});

const dots = document.querySelectorAll(".dot");

function updateCarousel() {
    track.style.transform = `translateX(-${index * 100}%)`;

    dots.forEach(dot => dot.classList.remove("active"));
    dots[index].classList.add("active");
}

function goTo(i) {
    index = i;
    resetAutoPlay();
    updateCarousel();
}

next.addEventListener("click", () => {
    index = (index + 1) % reviews.length;
    resetAutoPlay();
    updateCarousel();
});

prev.addEventListener("click", () => {
    index = (index - 1 + reviews.length) % reviews.length;
    resetAutoPlay();
    updateCarousel();
});

// AUTO PLAY
let auto = setInterval(() => {
    index = (index + 1) % reviews.length;
    updateCarousel();
}, 4000);

function resetAutoPlay() {
    clearInterval(auto);
    auto = setInterval(() => {
        index = (index + 1) % reviews.length;
        updateCarousel();
    }, 4000);
}

updateCarousel();
