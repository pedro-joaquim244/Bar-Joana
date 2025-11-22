const slides = document.querySelectorAll(".slide");
let index = 0;

// Função de digitar o texto
function typeWriter(element, text) {
  if (!element || !text) return;

  element.innerHTML = "";
  let i = 0;
  const speed = 45;

  function typing() {
    if (i < text.length) {
      element.innerHTML += text.charAt(i);
      i++;
      setTimeout(typing, speed);
    }
  }

  typing();
}

// Troca os slides
function showSlide() {
  slides.forEach(s => s.classList.remove("active"));

  const current = slides[index];
  current.classList.add("active");

  const text = current.getAttribute("data-text");
  const h1 = current.querySelector("h1");

  typeWriter(h1, text);

  index = (index + 1) % slides.length;
}

// Iniciar carrossel
showSlide();
setInterval(showSlide, 5000);
