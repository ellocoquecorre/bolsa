<?php
require_once '../../config/config.php';
include '../funciones/cliente_funciones.php';

$cliente_id = isset($_GET['cliente_id']) ? $_GET['cliente_id'] : 1;

$datos_corredora = obtenerDatosCorredora($cliente_id);
$url_corredora = $datos_corredora['url'];
$nombre_corredora = $datos_corredora['corredora'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización de dólares - Goodfellas Inc.</title>
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
        <img src="../img/frases.gif" alt="Frases" class="frase-img">
    </div>
    <!-- FIN PRELOADER -->

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../img/logo.png" alt="Logo" title="GoodFellas" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle nav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="cliente.php?cliente_id=<?php echo $cliente_id; ?>"><i class="fa-solid fa-house me-2"></i>Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="historial.php?cliente_id=<?php echo $cliente_id; ?>"><i class="fa-solid fa-hourglass me-2"></i>Historial</a>
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
            <h4 class="fancy"><?php echo htmlspecialchars($nombre . ' ' . $apellido); ?></h4>
            <p>Tu corredora es<br><a href="<?php echo $url_corredora; ?>" class="btn btn-custom ver"><i class="fas fa-hand-pointer me-2"></i><?php echo $nombre_corredora; ?></a></p>
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

        <hr class="mod" style="margin-bottom: 80px;">


    </div>
    <!-- FIN CONTENIDO -->

    <!-- FOOTER -->
    <footer class="footer bg-light">
        <a href="https://www.afip.gob.ar/" target="_blank" rel="noopener noreferrer">
            <img id="fixed-image" src="../img/chorro.png" alt="" data-bs-toggle="tooltip"
                data-bs-placement="top" title="Hacé click... dale..." />
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
    <script src="../js/botones_pesos_dolares.js"></script>
    <script src="../js/valor_promedio_ccl.js"></script>
    <script src="../js/preloader.js"></script>
    <script src="../js/fixedImage.js"></script>
    <!-- FIN JS -->
</body>

</html>