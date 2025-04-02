<?php
// Incluir archivo de configuración
require_once '../../config/config.php';

// Incluir las funciones necesarias
include '../funciones/cliente_funciones.php';

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
    <!-- PRELOADER -->
    <div id="preloader">
        <img src="../img/preloader.gif" alt="Preloader" class="main-img">
    </div>
    <!-- FIN PRELOADER -->

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../img/logo.png" alt="Logo" title="GoodFellas" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="lista_clientes.php"><i class="fa-solid fa-users me-2"></i>Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="alta_clientes.php"><i class="fa-solid fa-user-plus me-2"></i>Alta Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="dolares.php"><i class="fa-solid fa-dollar-sign me-2"></i>Dólares</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php"><i class="fa-solid fa-power-off me-2"></i>Salir</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- NAVBAR -->

    <!-- CONTENIDO -->
    <div class="row mx-2 mt-navbar">

        <!-- TITULO -->
        <div class="col-12 text-center">
            <h4 class="fancy">Cotización del dólar</h4>
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <!-- COTIZACION DOLAR -->
        <div class="col-12 text-center">
            <div class="container-fluid my-4 efectivo" id="dolar">
                <h5 class="me-2 cartera titulo-botones mb-4">Tipos de cambio</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th colspan="3">Oficial</th>
                                <th colspan="3">Blue</th>
                                <th colspan="3">Bolsa</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Compra</td>
                                <td>Venta</td>
                                <td>Promedio</td>
                                <td>Compra</td>
                                <td>Venta</td>
                                <td>Promedio</td>
                                <td>Compra</td>
                                <td>Venta</td>
                                <td>Promedio</td>
                            </tr>
                            <tr>
                                <td>$ <?php echo formatear_dinero($oficial_compra); ?></td>
                                <td>$ <?php echo formatear_dinero($oficial_venta); ?></td>
                                <td>$ <?php echo formatear_dinero(($oficial_compra + $oficial_venta) / 2); ?></td>
                                <td>$ <?php echo formatear_dinero($blue_compra); ?></td>
                                <td>$ <?php echo formatear_dinero($blue_venta); ?></td>
                                <td>$ <?php echo formatear_dinero(($blue_compra + $blue_venta) / 2); ?></td>
                                <td>$ <?php echo formatear_dinero($bolsa_compra); ?></td>
                                <td>$ <?php echo formatear_dinero($bolsa_venta); ?></td>
                                <td>$ <?php echo formatear_dinero(($bolsa_compra + $bolsa_venta) / 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr class="linea-accion">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th colspan="3">CCL</th>
                                <th colspan="3">Tarjeta</th>
                                <th colspan="3">Mayorista</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Compra</td>
                                <td>Venta</td>
                                <td>Promedio</td>
                                <td>Compra</td>
                                <td>Venta</td>
                                <td>Promedio</td>
                                <td>Compra</td>
                                <td>Venta</td>
                                <td>Promedio</td>
                            </tr>
                            <tr>
                                <td>$ <?php echo formatear_dinero($contadoconliqui_compra); ?></td>
                                <td>$ <?php echo formatear_dinero($contadoconliqui_venta); ?></td>
                                <td>$ <?php echo formatear_dinero(($contadoconliqui_compra + $contadoconliqui_venta) / 2); ?></td>
                                <td>$ <?php echo formatear_dinero($tarjeta_compra); ?></td>
                                <td>$ <?php echo formatear_dinero($tarjeta_venta); ?></td>
                                <td>$ <?php echo formatear_dinero(($tarjeta_compra + $tarjeta_venta) / 2); ?></td>
                                <td>$ <?php echo formatear_dinero($mayorista_compra); ?></td>
                                <td>$ <?php echo formatear_dinero($mayorista_venta); ?></td>
                                <td>$ <?php echo formatear_dinero(($mayorista_compra + $mayorista_venta) / 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- FIN COTIZACION DOLAR -->

        <hr class="mod">

    </div>

    <!-- FIN CONTENIDO -->

    <!-- FOOTER -->
    <footer class="footer bg-light">
        <a href="https://www.afip.gob.ar/" target="_blank" rel="noopener noreferrer">
            <img id="fixed-image" src="../img/chorro.png" alt="" data-bs-toggle="tooltip"
                data-bs-placement="top" title="Puede darme dinero?" />
        </a>
        <div class="container">
            <span class="text-muted">© GoodFellas Inc.</span>
        </div>
    </footer>
    <!-- FIN FOOTER -->

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="../js/tooltip.js"></script>
    <script src="../js/preloader.js"></script>
    <script src="../js/fixedImage.js"></script>
    <!-- FIN JS -->

</body>

</html>