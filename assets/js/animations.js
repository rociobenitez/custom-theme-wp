// Verifica que GSAP esté cargado
console.log("GSAP:", gsap ? "GSAP está disponible" : "GSAP no está cargado");

document.addEventListener("DOMContentLoaded", function () {
  gsap.registerPlugin(ScrollTrigger); // Asegura que ScrollTrigger esté disponible

  gsap.from(".specialty-card", {
    opacity: 0, // Parte desde opacidad 0 (invisible)
    y: 50, // Desplaza hacia abajo 50px
    duration: 1, // Duración de la animación
    stagger: 0.3, // Retraso entre cada card
    ease: "power2.out", // Suaviza la animación
    scrollTrigger: {
      trigger: ".specialty-card",
      start: "top 80%", // La animación inicia cuando el elemento está al 80% de la pantalla
      toggleActions: "play none none none", // Controla la animación solo al mostrar
    },
  });
});
