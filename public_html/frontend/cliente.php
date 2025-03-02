<?php
session_start();
require_once '../../config/config.php';

// Incluir las funciones necesarias
include '../funciones/cliente_funciones.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit;
}

// Obtener el email del usuario logueado
$email = $_SESSION['email'];

// Crear conexión a la base de datos
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar la conexión
if ($conn->connect_error) {
    die("La conexión ha fallado: " . $conn->connect_error);
}

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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle nav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../backend/historial.php"><i class="fa-solid fa-clock-rotate-left me-2"></i>Historial</a>
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
            <h4 class="fancy"><?php echo htmlspecialchars($nombre . ' ' . htmlspecialchars($apellido)); ?></h4>
            <p>Tu corredora es<br><a href="<!-- link_corredora -->" class="btn btn-custom ver"><i class="fas fa-hand-pointer me-2"></i><!-- nombre_corredora --></a></p>
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
                                    <th>Valor Total Inicial</th>
                                    <th>Valor Total Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $acciones = obtenerAcciones($cliente_id);
                                $valor_inicial_consolidado_acciones_pesos = 0;
                                $valor_actual_consolidado_acciones_pesos = 0;

                                foreach ($acciones as $accion) {
                                    $precio_actual = obtenerPrecioActualGoogleFinance($accion['ticker']);
                                    $valor_inicial_acciones_pesos = $accion['precio'] * $accion['cantidad'];
                                    $valor_inicial_consolidado_acciones_pesos += $valor_inicial_acciones_pesos;
                                    $valor_actual_acciones_pesos = $precio_actual * $accion['cantidad'];
                                    $valor_actual_consolidado_acciones_pesos += $valor_actual_acciones_pesos;
                                }
                                $rendimiento_consolidado_acciones_pesos = $valor_actual_consolidado_acciones_pesos - $valor_inicial_consolidado_acciones_pesos;
                                $rentabilidad_consolidado_acciones_pesos = (($valor_actual_consolidado_acciones_pesos - $valor_inicial_consolidado_acciones_pesos) / $valor_inicial_consolidado_acciones_pesos) * 100;
                                ?>

                                <tr>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_acciones_pesos)); ?></td>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_acciones_pesos)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_acciones_pesos); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_acciones_pesos); ?></td>

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
                                    <th rowspan="2" style="vertical-align: text-top;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Fecha</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Cantidad</th>
                                    <th colspan="2">Precio x acción</th>
                                    <th colspan="2">Valor total</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rentabilidad</th>
                                </tr>
                                <tr>
                                    <th><!-- Precio -->Compra</th>
                                    <th><!-- Precio -->Hoy</th>
                                    <th><!-- Valor -->Compra</th>
                                    <th><!-- Valor -->Hoy</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-acciones-pesos">
                                <?php
                                $acciones = obtenerAcciones($cliente_id);
                                foreach ($acciones as $accion) {
                                    $precio_actual = obtenerPrecioActualGoogleFinance($accion['ticker']);
                                    $valor_inicial_acciones_pesos = $accion['precio'] * $accion['cantidad'];
                                    $valor_actual_acciones_pesos = $precio_actual * $accion['cantidad'];
                                    $rendimiento_acciones_pesos = $valor_actual_acciones_pesos - $valor_inicial_acciones_pesos;
                                    $rentabilidad_acciones_pesos = (($rendimiento_acciones_pesos) / $valor_inicial_acciones_pesos) * 100;

                                    echo "<tr data-ticker='{$accion['ticker']}'>
                                    <td>{$accion['ticker']}</td>
                                    <td>" . htmlspecialchars(formatearFecha($accion['fecha'])) . "</td>
                                    <td>{$accion['cantidad']}</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($accion['precio'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($precio_actual)) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($valor_inicial_acciones_pesos)) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($valor_actual_acciones_pesos)) . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_acciones_pesos) . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_acciones_pesos) . "</td>
                                </tr>";
                                }
                                ?>
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
                                    <th>Valor Total Inicial</th>
                                    <th>Valor Total Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $acciones = obtenerAcciones($cliente_id);
                                $valor_inicial_consolidado_acciones_dolares = 0;
                                $valor_actual_consolidado_acciones_dolares = 0;
                                $valor_inicial_ccl = obtenerCCLCompra($cliente_id, $accion['ticker']);
                                foreach ($acciones as $accion) {
                                    $precio_actual = obtenerPrecioActualGoogleFinance($accion['ticker']);
                                    $valor_inicial_acciones_dolares = ($accion['precio'] * $accion['cantidad']) / $valor_inicial_ccl;
                                    $valor_inicial_consolidado_acciones_dolares += $valor_inicial_acciones_dolares;
                                    $valor_actual_acciones_dolares = ($precio_actual * $accion['cantidad']) / $promedio_ccl;
                                    $valor_actual_consolidado_acciones_dolares += $valor_actual_acciones_dolares;
                                }
                                $rendimiento_consolidado_acciones_dolares = $valor_actual_consolidado_acciones_dolares - $valor_inicial_consolidado_acciones_dolares;
                                $rentabilidad_consolidado_acciones_dolares = (($valor_actual_consolidado_acciones_dolares - $valor_inicial_consolidado_acciones_dolares) / $valor_inicial_consolidado_acciones_dolares) * 100;
                                ?>
                                <tr>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_acciones_dolares)); ?></td>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_acciones_dolares)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_acciones_dolares, 'u$s'); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_acciones_dolares); ?></td>

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
                                    <th rowspan="2" style="vertical-align: text-top;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Fecha</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Cantidad</th>
                                    <th colspan="2">Dólar CCL</th>
                                    <th colspan="2">Precio x acción</th>
                                    <th colspan="2">Valor total</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rentabilidad</th>
                                </tr>
                                <tr>
                                    <th><!-- Valor CCL -->Compra</th>
                                    <th><!-- Valor CCL -->Hoy</th>
                                    <th><!-- Precio -->Compra</th>
                                    <th><!-- Precio -->Hoy</th>
                                    <th><!-- X -->Compra</th>
                                    <th><!-- X -->Hoy</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-acciones-dolares">
                                <?php
                                $acciones = obtenerAcciones($cliente_id);
                                foreach ($acciones as $accion) {
                                    // pesos
                                    $precio_actual = obtenerPrecioActualGoogleFinance($accion['ticker']);
                                    $valor_inicial_acciones_pesos = $accion['precio'] * $accion['cantidad'];
                                    $valor_actual_acciones_pesos = $precio_actual * $accion['cantidad'];
                                    // dolares
                                    $valor_inicial_ccl = obtenerCCLCompra($cliente_id, $accion['ticker']);
                                    $precio_actual_dolares = $precio_actual / $promedio_ccl;
                                    $valor_inicial_acciones_dolares = $valor_inicial_acciones_pesos / $valor_inicial_ccl;
                                    $valor_actual_acciones_dolares = $valor_actual_acciones_pesos / $promedio_ccl;
                                    $rendimiento_acciones_dolares = $valor_actual_acciones_dolares - $valor_inicial_acciones_dolares;
                                    $rentabilidad_acciones_dolares = (($valor_actual_acciones_dolares - $valor_inicial_acciones_dolares) / $valor_inicial_acciones_dolares) * 100;


                                    echo "<tr data-ticker='{$accion['ticker']}'>
                                    <td>{$accion['ticker']}</td>
                                    <td>" . htmlspecialchars(formatearFecha($accion['fecha'])) . "</td>
                                    <td>{$accion['cantidad']}</td>
                                    <td class='text-right'>$ " . htmlspecialchars(obtenerCCLCompra($cliente_id, $accion['ticker'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($promedio_ccl)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($accion['precio'] / $valor_inicial_ccl)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($precio_actual_dolares)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($valor_inicial_acciones_dolares)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($valor_actual_acciones_dolares)) . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_acciones_dolares, 'u$s') . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_acciones_dolares) . "</td>
                                </tr>";
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                    <!-- Fin Completa Acciones Dólares -->

                </div>
                <!-- Fin Acciones Dólares -->

            </div>
        </div>
        <!-- FIN ACCIONES -->

        <hr class="mod">

        <!-- COTIZACION DOLAR -->
        <div class="col-12 text-center">
            <div class="container-fluid my-4 efectivo" id="dolar">
                <h5 class="me-2 cartera titulo-botones mb-4">Tipos de cambio</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th colspan="2">Oficial</th>
                                <th colspan="2">Blue</th>
                                <th colspan="2">Bolsa</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Compra</td>
                                <td>Venta</td>
                                <td>Compra</td>
                                <td>Venta</td>
                                <td>Compra</td>
                                <td>Venta</td>
                            </tr>
                            <tr>
                                <td>$ <?php echo formatear_dinero($oficial_compra); ?></td>
                                <td>$ <?php echo formatear_dinero($oficial_venta); ?></td>
                                <td>$ <?php echo formatear_dinero($blue_compra); ?></td>
                                <td>$ <?php echo formatear_dinero($blue_venta); ?></td>
                                <td>$ <?php echo formatear_dinero($bolsa_compra); ?></td>
                                <td>$ <?php echo formatear_dinero($bolsa_venta); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr class="linea-accion">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th colspan="2">CCL</th>
                                <th colspan="2">Tarjeta</th>
                                <th colspan="2">Mayorista</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Compra</td>
                                <td>Venta</td>
                                <td>Compra</td>
                                <td>Venta</td>
                                <td>Compra</td>
                                <td>Venta</td>
                            </tr>
                            <tr>
                                <td>$ <?php echo formatear_dinero($contadoconliqui_compra); ?></td>
                                <td>$ <?php echo formatear_dinero($contadoconliqui_venta); ?></td>
                                <td>$ <?php echo formatear_dinero($tarjeta_compra); ?></td>
                                <td>$ <?php echo formatear_dinero($tarjeta_venta); ?></td>
                                <td>$ <?php echo formatear_dinero($mayorista_compra); ?></td>
                                <td>$ <?php echo formatear_dinero($mayorista_venta); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- COTIZACION DOLAR -->

        <hr class="mod">

        <!-- BALANCE -->
        <div class="col-12 text-center">
            <div class="container-fluid my-4 efectivo" id="efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Efectivo</h5>
                <div class="row">
                    <div class="col-12 col-md-4 text-start">
                        <p class="saldo-pesos">Saldo en pesos: $ <?php echo $saldo_en_pesos_formateado; ?></p>
                    </div>
                    <div class="col-12 col-md-6 text-start">
                        <p class="saldo-dolares">Saldo en dólares: u$s <?php echo $saldo_en_dolares_formateado; ?>
                            <small class="promedio_ccl">(Promedio dólar CCL: $ <?php echo (formatear_dinero($promedio_ccl)); ?>)</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN BALANCE -->

        <hr class="mod" style="margin-bottom: 80px;">

    </div>
    <!-- FIN CONTENIDO -->

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