document.addEventListener("DOMContentLoaded", function() {
  const phrases = [
      "¿Falta mucho?",
      "Aguantá, que esto no es delivery exprés.",
      "¿Y ahora?",
      "Respirá hondo… o por lo menos intentá no putear.",
      "¿Listo?",
      "Si tarda más, te mando un café de cortesía.",
      "¡¡Me aburro!!",
      "Falta menos… o eso quiero creer.",
      "Poquito mas...",
      "Aprovechá y aprendé un idioma nuevo.",
      "Apurate que la tortuga de la fábula ya me pasó DOS veces!",
      "Ya casi…",
  ];

  let currentPhraseIndex = 0;
  const loadingTextElement = document.getElementById("loading-text");

  function updateLoadingText() {
      loadingTextElement.textContent = phrases[currentPhraseIndex];
      currentPhraseIndex = (currentPhraseIndex + 1) % phrases.length;
  }

  updateLoadingText();
  setInterval(updateLoadingText, 2000);
});