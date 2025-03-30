document.addEventListener("DOMContentLoaded", function () {
    const image = document.getElementById("fixed-image");

    // Tiempo aleatorio entre 5s y 15s (en milisegundos)
    const appearDelay = Math.floor(Math.random() * (10000 - 5000 + 1)) + 5000;

    // Tiempo aleatorio entre 20s y 45s para permanecer visible
    const visibleDuration = Math.floor(Math.random() * (10000 - 5000 + 1)) + 5000;

    setTimeout(() => {
        image.classList.add("show");
        image.classList.remove("hide");

        setTimeout(() => {
            image.classList.add("hide");
            image.classList.remove("show");
        }, visibleDuration);

    }, appearDelay);
});
