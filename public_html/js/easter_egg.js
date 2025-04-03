document.addEventListener("DOMContentLoaded", function () {
    // Probabilidad de que el script se ejecute (ajusta el 0.5 según lo que quieras)
    if (Math.random() < 0.5) {  
        const image = document.getElementById("fixed-image");

        // Aparece entre 5s y 15s (en milisegundos)
        const appearDelay = Math.floor(Math.random() * (10000 - 5000 + 1)) + 5000;

        // Permanece entre 5s y 15s para permanecer visible
        const visibleDuration = Math.floor(Math.random() * (10000 - 5000 + 1)) + 5000;

        setTimeout(() => {
            image.classList.add("show");
            image.classList.remove("hide");

            setTimeout(() => {
                image.classList.add("hide");
                image.classList.remove("show");
            }, visibleDuration);

        }, appearDelay);
    }
});
