document.addEventListener("DOMContentLoaded", function() {
    const phrases = [
        "Haciendo que laburo mientras espero que esto cargue.",
        "Dale máquina, que la ansiedad me está ganando por goleada.",
        "Esperame un cachito… o dos, por las dudas.",
        "Si esperaste el 60 un feriado, esto es un trámite.",
        "Falta menos… o eso quiero creer.",
        "Paciencia, m’hijo… o vaya a tomarse un fernet mientras.",
        "Sigo esperando… ya soy patrimonio de la impaciencia.",
        "Cargando… mientras tanto, podés aprender un idioma nuevo.",
        "Aguantá, que esto no es delivery exprés.",
        "¿Hola? ¿Hay alguien ahí o sigo esperando solo?",
        "Respirá hondo… o por lo menos intentá no putear.",
        "Si tarda más, te mando un café de cortesía.",
        "Mandando fruta con estilo mientras espero que cargue.",
        "Apurate que la tortuga de la fábula ya me pasó DOS veces.",
        "Calculando cuánto falta… y ya me cansé de calcular.",
        "Tratando de no romper nada mientas espero que cargue de una vez.",
        "Esperando con café y dignidad.",
        "Cebándole mate a los unicornios… porque esto tarda un siglo.",
        "Sacándole viruta al teclado de tanto actualizar."
    ];

    let currentPhraseIndex = 0;
    const loadingTextElement = document.getElementById("loading-text");

    function updateLoadingText() {
        loadingTextElement.textContent = phrases[currentPhraseIndex];
        currentPhraseIndex = (currentPhraseIndex + 1) % phrases.length;
    }

    updateLoadingText();
    setInterval(updateLoadingText, 3000);
});