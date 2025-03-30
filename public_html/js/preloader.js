document.addEventListener("DOMContentLoaded", function() {
  // Ocultar preloader después de que la página esté completamente cargada
  window.onload = function() {
      setTimeout(function() {
          document.getElementById("preloader").style.display = "none";
      }, 500); // Reducido a 500ms para ocultar el preloader más rápido
  };
});