<?php
// Iniciar sesión para mostrar un mensaje adecuado en caso de que el usuario intente acceder sin permisos
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goodfellas Inc.</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <!-- FIN CSS -->
</head>

<body>


    <!-- CONTENIDO -->
    <div class="row mx-2 mt-navbar">

        <!-- TITULO -->
        <div class="col-12 text-center">
            <h4 class="fancy">Acceso no autorizado</h4>
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <!-- COMPRA ACCIONES -->
        <div class="col-3"></div>
        <div class="col-6 text-center">
            <div class="container-fluid my-4 efectivo" style="height: 350px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; overflow: hidden;">
                <img src="../img/no.gif" alt="Imagen Fija" style="max-height: 80%; width: auto;" />
                <p>No tenés permiso para ver esa página.</p>
                <a href="../login.php" class="btn btn-custom ver">Salí de acá&nbsp;&nbsp;<i class="fa-solid fa-person-running"></i></a>
            </div>
        </div>
        <div class="col-3"></div>
        <!-- FIN COMPRA ACCIONES -->

    </div>
    <!-- FIN CONTENIDO -->

    <!-- FOOTER -->
    <footer class="footer bg-light">
        <img id="fixed-image" src="../img/chorro.png" alt="Imagen Fija" />
        <div class="container">
            <span class="text-muted">© GoodFellas Inc.</span>
        </div>
    </footer>
    <!-- FIN FOOTER -->

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- FIN JS -->
</body>

</html>