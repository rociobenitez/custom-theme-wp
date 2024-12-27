document.addEventListener("DOMContentLoaded", function () {
  const header = document.querySelector("header.header");
  const scrollToTopButton = document.querySelector(".scroll-to-top");
  const dropdownToggles = document.querySelectorAll(".dropdown-toggle");
  const galleryBlock = document.querySelector(".gallery-block");

  const scrollThreshold = 100;
  let lastScrollTop = 0;

  dropdownToggles.forEach((toggle) => {
    const submenu = toggle.nextElementSibling;

    // Verifica que el submenú existe antes de agregar eventos
    if (!submenu || !submenu.classList.contains("dropdown-submenu")) {
      return;
    }

    // Despliegue en pantallas grandes (hover)
    toggle.parentElement.addEventListener("mouseenter", function () {
      submenu.classList.add("show");
    });

    toggle.parentElement.addEventListener("mouseleave", function () {
      submenu.classList.remove("show");
    });

    // Para pantallas pequeñas y medianas (click)
    toggle.addEventListener("click", function (e) {
      e.preventDefault(); // Evitar redirección
      submenu.classList.toggle("show");
    });
  });

  // Cerrar submenús al hacer clic fuera del menú
  document.addEventListener("click", function (e) {
    if (!e.target.closest(".nav-item")) {
      document.querySelectorAll(".dropdown-submenu.show").forEach((submenu) => {
        submenu.classList.remove("show");
      });
    }
  });

  // Mostrar/ocultar header según el scroll
  window.addEventListener("scroll", function () {
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > scrollThreshold) {
      if (scrollTop > lastScrollTop) {
        header.classList.remove("scroll-up");
        header.classList.add("scroll-down");
      } else {
        header.classList.remove("scroll-down");
        header.classList.add("scroll-up");
      }
      header.classList.add("scrolled");
    } else {
      header.classList.remove("scroll-down", "scroll-up", "scrolled");
    }

    lastScrollTop = Math.max(scrollTop, 0); // Evitar valores negativos
  });

  // Añade el evento de click al botón de scroll to top, si existe
  if (scrollToTopButton) {
    scrollToTopButton.addEventListener("click", function (e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    });
  }

  // Verificar si existe el elemento con la clase "gallery-block"
  if (galleryBlock) {
    const galleryItems = document.querySelectorAll(".gallery-item");
    const modalImage = document.getElementById("galleryModalImage");

    if (galleryItems && modalImage) {
      galleryItems.forEach((item) => {
        item.addEventListener("click", function () {
          const imageUrl = this.getAttribute("data-bs-image");
          modalImage.setAttribute("src", imageUrl);
        });
      });
    }
  }
});
