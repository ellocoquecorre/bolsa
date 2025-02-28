<?php
// Incluir archivo de configuración
require_once '../../config/config.php';

// Incluir las funciones necesarias
include '../funciones/cliente_funciones.php';

// Obtener el id del cliente desde la URL
$cliente_id = isset($_GET['cliente_id']) ? $_GET['cliente_id'] : 1;

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
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../img/logo.png" alt="Logo" title="GoodFellas" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="lista_clientes.php"><i class="fa-solid fa-users me-2"></i>Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="alta_clientes.php"><i class="fa-solid fa-user-plus me-2"></i>Alta Clientes</a>
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
            <h4 class="fancy">Historial de <?php echo htmlspecialchars($nombre . ' ' . $apellido); ?></h4>
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <!-- RESUMEN -->
        <div class="col-12 text-center">
            <div class="container-fluid my-4 efectivo" id="resumen">
                <h5 class="me-2 cartera titulo-botones mb-4">Resumen</h5>

                <!-- Botones -->
                <div class="text-start">
                    <div class="btn-group mb-3" role="group">
                        <button id="btnResumenPesos" class="btn btn-custom ver active">Posición en Pesos</button>
                        <button id="btnResumenDolares" class="btn btn-custom ver">Posición en Dólares</button>
                    </div>
                </div>
                <!-- Fin Botones -->

                <!-- Resumen Pesos -->
                <div id="tablaResumenPesos">
                    <p>pesos</p>
                </div>
                <!-- Fin Resumen Pesos -->

                <!-- Resumen Dólares -->
                <div id="tablaResumenDolares" class="d-none">
                    <p>dólares</p>
                </div>
                <!-- Fin Resumen Dólares -->

            </div>
        </div>
        <!-- FIN RESUMEN -->

        <hr class="mod">

        <!-- ACCIONES -->
        <div class="col-12 text-center">
            <div class="container-fluid my-4 efectivo" id="acciones">
                <h5 class="me-2 cartera titulo-botones mb-4">Acciones</h5>

                <!-- Botones -->
                <div class="text-start">
                    <div class="btn-group mb-3" role="group">
                        <button id="btnAccionesPesos" class="btn btn-custom ver active">Posición en Pesos</button>
                        <button id="btnAccionesDolares" class="btn btn-custom ver">Posición en Dólares</button>
                    </div>
                </div>
                <!-- Fin Botones -->

                <!-- Acciones Pesos -->
                <div id="tablaAccionesPesos">

                    <!-- Consolidada Acciones Pesos -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Valor Inicial</th>
                                    <th>Valor Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>$ <!-- valor_inicial_consolidado_acciones_pesos --></td>
                                    <td>$ <!-- valor_actual_consolidado_acciones_pesos --></td>
                                    <td>$ <!-- rendimiento_consolidado_acciones_pesos --></td>
                                    <td><!-- rentabilidad_consolidado_acciones_pesos --> %</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Acciones Pesos -->

                    <hr class="linea-accion">

                    <!-- Completa Acciones Pesos -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="vertical-align: text-top;">Ticker</th>
                                    <th style="vertical-align: text-top;">Cantidad</th>
                                    <th>Fecha<br>Compra</th>
                                    <th>Precio<br>Compra</th>
                                    <th>Total<br>Compra</th>
                                    <th>Fecha<br>Venta</th>
                                    <th>Precio<br>Venta</th>
                                    <th>Total<br>Venta</th>
                                    <th style="vertical-align: text-top;">Rendimiento</th>
                                    <th style="vertical-align: text-top;">Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-acciones-pesos">
                                <tr data-ticker="">
                                    <td><!-- ticker_accion --></td>
                                    <td><!-- cantidad_accion --></td>
                                    <td><!-- fecha_compra_accion --></td>
                                    <td class="text-right">$ <!-- precio_compra_pesos_accion --></td>
                                    <td class="text-right">$ <!-- total_compra_pesos_accion --></td>
                                    <td><!-- fecha_venta_accion --></td>
                                    <td class="text-right">$ <!-- precio_venta_pesos_accion --></td>
                                    <td class="text-right">$ <!-- total_venta_pesos_accion --></td>
                                    <td class="text-right">$ <!-- rendimiento_pesos_accion --></td>
                                    <td class="text-right"><!-- rentabilidad_pesos_accion --> %</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Completa Acciones Pesos -->

                </div>
                <!-- Fin Acciones Pesos -->

                <!-- Acciones Dólares -->
                <div id="tablaAccionesDolares" class="d-none">

                    <!-- Consolidada Acciones Dólares -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Valor Inicial</th>
                                    <th>Valor Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>$ <!-- valor_inicial_consolidado_acciones_dolares --></td>
                                    <td>$ <!-- valor_actual_consolidado_acciones_dolares --></td>
                                    <td>$ <!-- rendimiento_consolidado_acciones_dolares --></td>
                                    <td><!-- rentabilidad_consolidado_acciones_dolares --> %</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Acciones Dólares -->

                    <hr class="linea-accion">

                    <!-- Completa Acciones Dólares -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="vertical-align: text-top;">Ticker</th>
                                    <th style="vertical-align: text-top;">Cantidad</th>
                                    <th>Fecha<br>Compra</th>
                                    <th>Valor CCL<br>Compra</th>
                                    <th>Precio<br>Compra</th>
                                    <th>Total<br>Compra</th>
                                    <th>Fecha<br>Venta</th>
                                    <th>Valor CCL<br>Compra</th>
                                    <th>Precio<br>Venta</th>
                                    <th>Total<br>Venta</th>
                                    <th style="vertical-align: text-top;">Rendimiento</th>
                                    <th style="vertical-align: text-top;">Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-acciones-dolares">
                                <tr data-ticker="">
                                    <td><!-- ticker_accion --></td>
                                    <td><!-- cantidad_accion --></td>
                                    <td><!-- fecha_compra_accion --></td>
                                    <td class="text-right">$ <!-- valor_ccl_compra_dolares_accion --></td>
                                    <td class="text-right">u$s <!-- precio_compra_dolares_accion --></td>
                                    <td class="text-right">u$s <!-- total_compra_dolares_accion --></td>
                                    <td><!-- fecha_venta_accion --></td>
                                    <td class="text-right">$ <!-- valor_ccl_venta_dolares_accion --></td>
                                    <td class="text-right">u$s <!-- precio_venta_dolares_accion --></td>
                                    <td class="text-right">u$s <!-- total_venta_dolares_accion --></td>
                                    <td class="text-right">u$s <!-- rendimiento_dolares_accion --></td>
                                    <td class="text-right"><!-- rentabilidad_dolares_accion --> %</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Completa Acciones Dólares -->

                </div>

            </div>
        </div>
        <!-- FIN ACCIONES -->

        <hr class="mod" style="margin-bottom: 80px;">

    </div>
    <!-- FIN CONTENIDO -->

    <!-- FOOTER -->
    <img id="fixed-image" src="../img/chorro.png" alt="Imagen Fija" />
    <footer class="footer bg-light">
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
    <script src="../js/formato_miles_balance.js"></script>
    <script src="../js/valor_promedio_ccl.js"></script>
    <!-- FIN JS -->
</body>

</html>