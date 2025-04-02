// public_html/js/preloader.js
document.addEventListener('DOMContentLoaded', function() {
  const preloader = document.getElementById('preloader');
  
  if (!preloader) {
      console.warn('Elemento preloader no encontrado');
      return;
  }

  // Función para ocultar el preloader
  function hidePreloader() {
      preloader.style.transition = 'opacity 0.5s ease, visibility 0.5s';
      preloader.style.opacity = '0';
      preloader.style.visibility = 'hidden';
      
      setTimeout(() => {
          preloader.style.display = 'none';
      }, 500);
  }

  // Evento principal para ocultar
  window.addEventListener('load', hidePreloader);

  // Fallback 1: Verificar periodicamente si la página está cargada
  const checkLoaded = setInterval(() => {
      if (document.readyState === 'complete') {
          clearInterval(checkLoaded);
          hidePreloader();
      }
  }, 100);

  // Fallback 2: Ocultar después de tiempo máximo
  setTimeout(hidePreloader, 5000);

  // Manejar errores de recursos
  window.addEventListener('error', (e) => {
      console.error('Error cargando recurso:', e);
      hidePreloader();
  }, true);
});

