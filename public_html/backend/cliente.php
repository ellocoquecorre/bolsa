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
            <h4 class="fancy"><?php echo htmlspecialchars($nombre . ' ' . $apellido); ?></h4>
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
                                    <th rowspan="2" style="vertical-align: text-top;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Fecha</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Cantidad</th>
                                    <th colspan="2">Precio</th>
                                    <th colspan="2">Valor</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rentabilidad</th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr>
                                    <th><!-- Precio -->Compra</th>
                                    <th><!-- Precio -->Hoy</th>
                                    <th><!-- Valor -->Inicial</th>
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
                                            <td class='text-center'>
                                                <div class='dropdown d-flex justify-content-end'>
                                                    <button class='btn custom-btn dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false' title='Opciones'>
                                                    <i class='fa-solid fa-bars'></i>
                                                    </button>
                                                    <ul class='dropdown-menu dropdown-menu-end' aria-labelledby='dropdownMenuButton'>
                                                        <li>
                                                        <a class='dropdown-item' href='#'><i class='fa-solid fa-percent me-2'></i> Venta parcial</a>
                                                        </li>
                                                        <li>
                                                        <a class='dropdown-item' href='../funciones/venta_total_acciones.php?cliente_id={$cliente_id}&ticker={$accion['ticker']}'><i class='fa-solid fa-check-circle me-2'></i> Venta total</a>
                                                        </li>
                                                        <li>
                                                        <a class='dropdown-item' href='../funciones/editar_compra_acciones.php?cliente_id={$cliente_id}&ticker={$accion['ticker']}'><i class='fa-solid fa-pen me-2'></i> Editar</a>
                                                        </li>
                                                        <li>
                                                        <a class='dropdown-item' href='#' onclick='eliminarAccion(this)'><i class='fa-solid fa-trash me-2'></i> Eliminar</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
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
                                    <th rowspan="2" style="vertical-align: text-top;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Fecha</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Cantidad</th>
                                    <th colspan="2">Dólar CCL</th>
                                    <th colspan="2">Precio</th>
                                    <th colspan="2">Valor</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rentabilidad</th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr>
                                    <th><!-- Valor CCL -->Compra</th>
                                    <th><!-- Valor CCL -->Hoy</th>
                                    <th><!-- Precio -->Compra</th>
                                    <th><!-- Precio -->Hoy</th>
                                    <th><!-- X -->Inicial</th>
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
                                            <td class='text-center'>
                                                <div class='dropdown d-flex justify-content-end'>
                                                    <button class='btn custom-btn dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false'>
                                                    <i class='fa-solid fa-bars'></i>
                                                    </button>
                                                    <ul class='dropdown-menu dropdown-menu-end' aria-labelledby='dropdownMenuButton'>
                                                        <li>
                                                        <a class='dropdown-item' href='#'><i class='fa-solid fa-percent me-2'></i> Venta parcial</a>
                                                        </li>
                                                        <li>
                                                        <a class='dropdown-item' href='../funciones/venta_total_acciones.php?cliente_id={$cliente_id}&ticker={$accion['ticker']}'><i class='fa-solid fa-check-circle me-2'></i> Venta total</a>
                                                        </li>
                                                        <li>
                                                        <a class='dropdown-item' href='../funciones/editar_compra_acciones.php?cliente_id={$cliente_id}&ticker={$accion['ticker']}'><i class='fa-solid fa-pen me-2'></i> Editar</a>
                                                        </li>
                                                        <li>
                                                        <a class='dropdown-item' href='#' onclick='eliminarAccion(this)'><i class='fa-solid fa-trash me-2'></i> Eliminar</a>

                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>";
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                    <!-- Fin Completa Acciones Dólares -->

                </div>
                <!-- Fin Acciones Dólares -->

                <hr class="linea-accion">

                <!-- Comprar Acciones -->
                <div class="text-start">
                    <a href="../funciones/compra_acciones.php?cliente_id=<?php echo $cliente_id; ?>" class="btn btn-custom ver">
                        <i class="fa-solid fa-cart-shopping me-2"></i>Comprar
                    </a>
                </div>
                <!-- Fin Comprar Acciones -->

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
                <hr class="linea-accion">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center">
                            <h6 class="me-2">Ingresar efectivo</h6>
                            <input type="text" id="ingresar_efectivo" placeholder="0,00" class="form-control me-2" onkeyup="formatoBalance(this)" style="width: 150px; text-align: right;">
                            <input type="button" value="+" class="btn btn-info btn-custom ver" id="ingresar_btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Ingresar efectivo" style="width: 40px;">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center">
                            <h6 class="me-2">Retirar efectivo</h6>
                            <input type="text" id="retirar_efectivo" placeholder="0,00" class="form-control me-2" onkeyup="formatoBalance(this)" style="width: 150px; text-align: right;">
                            <input type="button" value="-" class="btn btn-info btn-custom eliminar" id="retirar_btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Retirar efectivo" style="width: 40px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN BALANCE -->

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
    <script src="../js/eliminar_acciones.js"></script>
    <script src="../js/ingresar_efectivo.js"></script>
    <script src="../js/formato_miles_balance.js"></script>
    <script src="../js/retirar_efectivo.js"></script>
    <script src="../js/valor_promedio_ccl.js"></script>
    <!-- FIN JS -->
</body>

</html>