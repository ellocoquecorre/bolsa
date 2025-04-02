<?php
// Incluir archivo de configuración
require_once '../../config/config.php';

// Incluir las funciones necesarias
include '../funciones/cliente_funciones.php';
include '../funciones/cliente_historial.php';

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
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
                <img src="../img/logo.png" alt="Logo" title="GoodFellas Inc." />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class=" navbar-toggler-icon"></span>
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
                        <a class="nav-link" href="dolares.php"><i class="fa-solid fa-dollar-sign me-2"></i>Dólares</a>
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
            <a href="cliente.php?cliente_id=<?php echo $cliente_id; ?>" class="btn btn-custom ver">
                <i class="fa-solid fa-magnifying-glass me-2"></i>Tenencia</a>
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

                <hr class="linea-accion">

                <!-- Pesos -->
                <div id="tablaResumenPesos">

                    <!-- Consolidada -->
                    <h6 class="me-2 cartera posiciones mb-4">Resumen Consolidado</h6>
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
                                $valor_compra_consolidado_historial_pesos = $valor_compra_consolidado_acciones_pesos + $valor_compra_consolidado_cedear_pesos + $valor_compra_consolidado_bonos_pesos + $valor_compra_consolidado_fondos_pesos;
                                $valor_venta_consolidado_historial_pesos = $valor_venta_consolidado_acciones_pesos + $valor_venta_consolidado_cedear_pesos + $valor_venta_consolidado_bonos_pesos + $valor_venta_consolidado_fondos_pesos;
                                $rendimiento_consolidado_historial_pesos = $valor_venta_consolidado_historial_pesos - $valor_compra_consolidado_historial_pesos;
                                if ($valor_compra_consolidado_historial_pesos != 0) {
                                    $rentabilidad_consolidado_historial_pesos = (($valor_venta_consolidado_historial_pesos - $valor_compra_consolidado_historial_pesos) / $valor_compra_consolidado_historial_pesos) * 100;
                                } else {
                                    $rentabilidad_consolidado_historial_pesos = 0; // O cualquier valor que consideres apropiado
                                }                                ?>
                                <tr>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_historial_pesos)); ?></td>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_historial_pesos)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_historial_pesos); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_historial_pesos); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada -->

                    <hr class="linea-accion">

                    <!-- Detalle -->
                    <h6 class="me-2 cartera posiciones mb-4">Resumen Completo</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Valor Inicial</th>
                                    <th>Valor Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Acciones</strong></td>
                                    <td class='text-right'>$ <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_acciones_pesos)); ?></td>
                                    <td class='text-right'>$ <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_acciones_pesos)); ?></td>
                                    <td class='text-right'><?php echo formatear_y_colorear_valor($rendimiento_consolidado_acciones_pesos); ?></td>
                                    <td class='text-right'><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_acciones_pesos); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Cedears</strong></td>
                                    <td class='text-right'>$ <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_cedear_pesos)); ?></td>
                                    <td class='text-right'>$ <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_cedear_pesos)); ?></td>
                                    <td class='text-right'><?php echo formatear_y_colorear_valor($rendimiento_consolidado_cedear_pesos); ?></td>
                                    <td class='text-right'><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_cedear_pesos); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Bonos</strong></td>
                                    <td class='text-right'>$ <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_bonos_pesos)); ?></td>
                                    <td class='text-right'>$ <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_bonos_pesos)); ?></td>
                                    <td class='text-right'><?php echo formatear_y_colorear_valor($rendimiento_consolidado_bonos_pesos); ?></td>
                                    <td class='text-right'><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_bonos_pesos); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Fondos</strong></td>
                                    <td class='text-right'>$ <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_fondos_pesos)); ?></td>
                                    <td class='text-right'>$ <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_fondos_pesos)); ?></td>
                                    <td class='text-right'><?php echo formatear_y_colorear_valor($rendimiento_consolidado_fondos_pesos); ?></td>
                                    <td class='text-right'><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_fondos_pesos); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Detalle -->

                </div>
                <!-- Fin Pesos -->

                <!-- Dolares -->
                <div id="tablaResumenDolares" class="d-none">

                    <!-- Consolidada -->
                    <h6 class="me-2 cartera posiciones mb-4">Resumen Consolidado</h6>
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
                                $valor_compra_consolidado_historial_dolares = $valor_compra_consolidado_acciones_dolares + $valor_compra_consolidado_cedear_dolares + $valor_compra_consolidado_bonos_dolares + $valor_compra_consolidado_fondos_dolares;
                                $valor_venta_consolidado_historial_dolares = $valor_venta_consolidado_acciones_dolares + $valor_venta_consolidado_cedear_dolares + $valor_venta_consolidado_bonos_dolares + $valor_venta_consolidado_fondos_dolares;
                                $rendimiento_consolidado_historial_dolares = $valor_venta_consolidado_historial_dolares - $valor_compra_consolidado_historial_dolares;
                                if ($valor_compra_consolidado_historial_dolares != 0) {
                                    $rentabilidad_consolidado_historial_dolares = (($valor_venta_consolidado_historial_dolares - $valor_compra_consolidado_historial_dolares) / $valor_compra_consolidado_historial_dolares) * 100;
                                } else {
                                    $rentabilidad_consolidado_historial_dolares = 0; // O cualquier valor que consideres apropiado
                                }                                 ?>
                                <tr>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_historial_dolares)); ?></td>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_historial_dolares)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_historial_dolares, 'u$s'); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_historial_dolares); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada -->

                    <hr class="linea-accion">

                    <!-- Detalle -->
                    <h6 class="me-2 cartera posiciones mb-4">Resumen Completo</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Valor Inicial</th>
                                    <th>Valor Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Acciones</strong></td>
                                    <td class='text-right'>u$s <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_acciones_dolares)); ?></td>
                                    <td class='text-right'>u$s <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_acciones_dolares)); ?></td>
                                    <td class='text-right'><?php echo formatear_y_colorear_valor($rendimiento_consolidado_acciones_dolares, 'u$s'); ?></td>
                                    <td class='text-right'><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_acciones_dolares); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Cedears</strong></td>
                                    <td class='text-right'>u$s <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_cedear_dolares)); ?></td>
                                    <td class='text-right'>u$s <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_cedear_dolares)); ?></td>
                                    <td class='text-right'><?php echo formatear_y_colorear_valor($rendimiento_consolidado_cedear_dolares, 'u$s'); ?></td>
                                    <td class='text-right'><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_cedear_dolares); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Bonos</strong></td>
                                    <td class='text-right'>u$s <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_bonos_dolares)); ?></td>
                                    <td class='text-right'>u$s <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_bonos_dolares)); ?></td>
                                    <td class='text-right'><?php echo formatear_y_colorear_valor($rendimiento_consolidado_bonos_dolares, 'u$s'); ?></td>
                                    <td class='text-right'><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_bonos_dolares); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Fondos</strong></td>
                                    <td class='text-right'>u$s <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_fondos_dolares)); ?></td>
                                    <td class='text-right'>u$s <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_fondos_dolares)); ?></td>
                                    <td class='text-right'><?php echo formatear_y_colorear_valor($rendimiento_consolidado_fondos_dolares, 'u$s'); ?></td>
                                    <td class='text-right'><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_fondos_dolares); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Detalle -->

                </div>
                <!-- Fin Dolares -->

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
                    <h6 class="me-2 cartera posiciones mb-4">Historial Consolidado</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Valor total Compra</th>
                                    <th>Valor total Venta</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_acciones_pesos)); ?></td>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_acciones_pesos)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_acciones_pesos); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_acciones_pesos); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Acciones Pesos -->

                    <hr class="linea-accion">

                    <!-- Completa Acciones Pesos -->
                    <h6 class="me-2 cartera posiciones mb-4">Historial Completo</h6>
                    <div class="table-responsive">
                        <table id="completa_acciones_pesos" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align: text-top;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Cantidad</th>
                                    <th colspan="3">Compra</th>
                                    <th colspan="3">Venta</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rentabilidad</th>
                                </tr>
                                <tr>
                                    <th>Fecha<!-- Compra --></th>
                                    <th>Precio<!-- Compra --></th>
                                    <th>Total<!-- Compra --></th>
                                    <th>Fecha<!-- Venta --></th>
                                    <th>Precio<!-- Venta --></th>
                                    <th>Total<!-- Venta --></th>
                                </tr>
                            </thead>
                            <tbody id="tabla-acciones-pesos">
                                <?php
                                foreach ($historial_acciones as $accion) {
                                    $total_compra_pesos_accion = $accion['cantidad'] * $accion['precio_compra'];
                                    $total_venta_pesos_accion = $accion['cantidad'] * $accion['precio_venta'];
                                    $rendimiento_pesos_accion = $total_venta_pesos_accion - $total_compra_pesos_accion;
                                    if ($total_compra_pesos_accion != 0) {
                                        $rentabilidad_pesos_accion = ($rendimiento_pesos_accion / $total_compra_pesos_accion) * 100;
                                    } else {
                                        $rentabilidad_pesos_accion = 0;
                                    }

                                    // Validar si fecha_compra y fecha_venta son fechas válidas antes de formatear
                                    $fecha_compra = DateTime::createFromFormat('Y-m-d', $accion['fecha_compra']);
                                    $fecha_venta = DateTime::createFromFormat('Y-m-d', $accion['fecha_venta']);

                                    echo "<tr data-ticker='{$accion['ticker']}'>
                                            <td>{$accion['ticker']}</td>
                                            <td class='text-right'>" . htmlspecialchars(formatear_numero($accion['cantidad'])) . "</td>
                                            <td>" . ($fecha_compra ? htmlspecialchars(formatearFecha($accion['fecha_compra'])) : 'Fecha inválida') . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($accion['precio_compra'])) . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($total_compra_pesos_accion)) . "</td>
                                            <td>" . ($fecha_venta ? htmlspecialchars(formatearFecha($accion['fecha_venta'])) : 'Fecha inválida') . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($accion['precio_venta'])) . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($total_venta_pesos_accion)) . "</td>
                                            <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_pesos_accion) . "</td>
                                            <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_pesos_accion) . "</td>
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
                    <h6 class="me-2 cartera posiciones mb-4">Historial Consolidado</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Valor total Compra</th>
                                    <th>Valor total Venta</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_acciones_dolares)); ?></td>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_acciones_dolares)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_acciones_dolares, 'u$s'); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_acciones_dolares); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Acciones Dólares -->

                    <hr class="linea-accion">

                    <!-- Completa Acciones Dólares -->
                    <h6 class="me-2 cartera posiciones mb-4">Historial Completo</h6>
                    <div class="table-responsive">
                        <table id="completa_acciones_dolares" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align: text-top;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Cantidad</th>
                                    <th colspan="4">Compra</th>
                                    <th colspan="4">Venta</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rentabilidad</th>
                                </tr>
                                <tr>
                                    <th>Fecha<!-- Compra --></th>
                                    <th>Dolar CCL<!-- Compra --></th>
                                    <th>Precio<!-- Compra --></th>
                                    <th>Total<!-- Compra --></th>
                                    <th>Fecha<!-- Venta --></th>
                                    <th>Dolar CCL<!-- Venta --></th>
                                    <th>Precio<!-- Venta --></th>
                                    <th>Total<!-- Venta --></th>
                                </tr>
                            </thead>
                            <tbody id="tabla-acciones-dolares">
                                <?php
                                foreach ($historial_acciones as $accion) {
                                    $precio_compra_dolares_accion = $accion['precio_compra'] / $accion['ccl_compra'];
                                    $total_compra_dolares_accion = $accion['cantidad'] * $precio_compra_dolares_accion;
                                    $precio_venta_dolares_accion = $accion['precio_venta'] / $accion['ccl_venta'];
                                    $total_venta_dolares_accion = $accion['cantidad'] * $precio_venta_dolares_accion;
                                    $rendimiento_dolares_accion = $total_venta_dolares_accion - $total_compra_dolares_accion;
                                    if ($total_compra_dolares_accion != 0) {
                                        $rentabilidad_dolares_accion = ($rendimiento_dolares_accion / $total_compra_dolares_accion) * 100;
                                    } else {
                                        $rentabilidad_dolares_accion = 0;
                                    }

                                    // Validar si fecha_compra y fecha_venta son fechas válidas antes de formatear
                                    $fecha_compra = DateTime::createFromFormat('Y-m-d', $accion['fecha_compra']);
                                    $fecha_venta = DateTime::createFromFormat('Y-m-d', $accion['fecha_venta']);

                                    echo "<tr data-ticker='{$accion['ticker']}'>
                                            <td>{$accion['ticker']}</td>
                                            <td class='text-right'>" . htmlspecialchars(formatear_numero($accion['cantidad'])) . "</td>
                                            <td>" . ($fecha_compra ? htmlspecialchars(formatearFecha($accion['fecha_compra'])) : 'Fecha inválida') . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($accion['ccl_compra'])) . "</td>
                                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($precio_compra_dolares_accion)) . "</td>
                                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($total_compra_dolares_accion)) . "</td>
                                            <td>" . ($fecha_venta ? htmlspecialchars(formatearFecha($accion['fecha_venta'])) : 'Fecha inválida') . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($accion['ccl_venta'])) . "</td>
                                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($precio_venta_dolares_accion)) . "</td>
                                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($total_venta_dolares_accion)) . "</td>
                                            <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_dolares_accion, 'u$s') . "</td>
                                            <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_dolares_accion) . "</td>
                                        </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Completa Acciones Dólares -->

                </div>

            </div>
        </div>
        <!-- FIN ACCIONES -->

        <hr class="mod">

        <!-- CEDEAR -->
        <div class="col-12 text-center">
            <div class="container-fluid my-4 efectivo" id="cedear">
                <h5 class="me-2 cartera titulo-botones mb-4">Cedears</h5>

                <!-- Botones -->
                <div class="text-start">
                    <div class="btn-group mb-3" role="group">
                        <button id="btnCedearPesos" class="btn btn-custom ver active">Posición en Pesos</button>
                        <button id="btnCedearDolares" class="btn btn-custom ver">Posición en Dólares</button>
                    </div>
                </div>
                <!-- Fin Botones -->

                <!-- Cedear Pesos -->
                <div id="tablaCedearPesos">

                    <!-- Consolidada Cedear Pesos -->
                    <h6 class="me-2 cartera posiciones mb-4">Historial Consolidado</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Valor total Compra</th>
                                    <th>Valor total Venta</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_cedear_pesos)); ?></td>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_cedear_pesos)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_cedear_pesos); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_cedear_pesos); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Cedear Pesos -->

                    <hr class="linea-accion">

                    <!-- Completa Cedear Pesos -->
                    <h6 class="me-2 cartera posiciones mb-4">Historial Completo</h6>
                    <div class="table-responsive">
                        <table id="completa_cedear_pesos" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align: text-top;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Cantidad</th>
                                    <th colspan="3">Compra</th>
                                    <th colspan="3">Venta</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rentabilidad</th>
                                </tr>
                                <tr>
                                    <th>Fecha<!-- Compra --></th>
                                    <th>Precio<!-- Compra --></th>
                                    <th>Total<!-- Compra --></th>
                                    <th>Fecha<!-- Venta --></th>
                                    <th>Precio<!-- Venta --></th>
                                    <th>Total<!-- Venta --></th>
                                </tr>
                            </thead>
                            <tbody id="tabla-cedear-pesos">
                                <?php
                                foreach ($historial_cedear as $cedear) {
                                    $total_compra_pesos_cedear = $cedear['cantidad_cedear'] * $cedear['precio_compra_cedear'];
                                    $total_venta_pesos_cedear = $cedear['cantidad_cedear'] * $cedear['precio_venta_cedear'];
                                    $rendimiento_pesos_cedear = $total_venta_pesos_cedear - $total_compra_pesos_cedear;
                                    if ($total_compra_pesos_cedear != 0) {
                                        $rentabilidad_pesos_cedear = ($rendimiento_pesos_cedear / $total_compra_pesos_cedear) * 100;
                                    } else {
                                        $rentabilidad_pesos_cedear = 0;
                                    }

                                    // Validar si fecha_compra y fecha_venta son fechas válidas antes de formatear
                                    $fecha_compra = DateTime::createFromFormat('Y-m-d', $cedear['fecha_compra_cedear']);
                                    $fecha_venta = DateTime::createFromFormat('Y-m-d', $cedear['fecha_venta_cedear']);

                                    echo "<tr data-ticker='{$cedear['ticker_cedear']}'>
                                            <td>{$cedear['ticker_cedear']}</td>
                                            <td class='text-right'>" . htmlspecialchars(formatear_numero($cedear['cantidad_cedear'])) . "</td>
                                            <td>" . ($fecha_compra ? htmlspecialchars(formatearFecha($cedear['fecha_compra_cedear'])) : 'Fecha inválida') . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($cedear['precio_compra_cedear'])) . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($total_compra_pesos_cedear)) . "</td>
                                            <td>" . ($fecha_venta ? htmlspecialchars(formatearFecha($cedear['fecha_venta_cedear'])) : 'Fecha inválida') . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($cedear['precio_venta_cedear'])) . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($total_venta_pesos_cedear)) . "</td>
                                            <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_pesos_cedear) . "</td>
                                            <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_pesos_cedear) . "</td>
                                        </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Completa Cedear Pesos -->

                </div>
                <!-- Fin Cedear Pesos -->

                <!-- Cedear Dólares -->
                <div id="tablaCedearDolares" class="d-none">

                    <!-- Consolidada Cedear Dólares -->
                    <h6 class="me-2 cartera posiciones mb-4">Historial Consolidado</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Valor total Compra</th>
                                    <th>Valor total Venta</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_cedear_dolares)); ?></td>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_cedear_dolares)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_cedear_dolares, 'u$s'); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_cedear_dolares); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Cedear Dólares -->

                    <hr class="linea-accion">

                    <!-- Completa Cedear Dólares -->
                    <h6 class="me-2 cartera posiciones mb-4">Historial Completo</h6>
                    <div class="table-responsive">
                        <table id="completa_cedear_dolares" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align: text-top;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Cantidad</th>
                                    <th colspan="4">Compra</th>
                                    <th colspan="4">Venta</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rentabilidad</th>
                                </tr>
                                <tr>
                                    <th>Fecha<!-- Compra --></th>
                                    <th>Dolar CCL<!-- Compra --></th>
                                    <th>Precio<!-- Compra --></th>
                                    <th>Total<!-- Compra --></th>
                                    <th>Fecha<!-- Venta --></th>
                                    <th>Dolar CCL<!-- Venta --></th>
                                    <th>Precio<!-- Venta --></th>
                                    <th>Total<!-- Venta --></th>
                                </tr>
                            </thead>
                            <tbody id="tabla-cedear-dolares">
                                <?php
                                foreach ($historial_cedear as $cedear) {
                                    $precio_compra_dolares_cedear = $cedear['precio_compra_cedear'] / $cedear['ccl_compra'];
                                    $total_compra_dolares_cedear = $cedear['cantidad_cedear'] * $precio_compra_dolares_cedear;
                                    $precio_venta_dolares_cedear = $cedear['precio_venta_cedear'] / $cedear['ccl_venta'];
                                    $total_venta_dolares_cedear = $cedear['cantidad_cedear'] * $precio_venta_dolares_cedear;
                                    $rendimiento_dolares_cedear = $total_venta_dolares_cedear - $total_compra_dolares_cedear;
                                    if ($total_compra_dolares_cedear != 0) {
                                        $rentabilidad_dolares_cedear = ($rendimiento_dolares_cedear / $total_compra_dolares_cedear) * 100;
                                    } else {
                                        $rentabilidad_dolares_cedear = 0;
                                    }

                                    // Validar si fecha_compra y fecha_venta son fechas válidas antes de formatear
                                    $fecha_compra = DateTime::createFromFormat('Y-m-d', $cedear['fecha_compra_cedear']);
                                    $fecha_venta = DateTime::createFromFormat('Y-m-d', $cedear['fecha_venta_cedear']);

                                    echo "<tr data-ticker='{$cedear['ticker_cedear']}'>
                                            <td>{$cedear['ticker_cedear']}</td>
                                            <td class='text-right'>" . htmlspecialchars(formatear_numero($cedear['cantidad_cedear'])) . "</td>
                                            <td>" . ($fecha_compra ? htmlspecialchars(formatearFecha($cedear['fecha_compra_cedear'])) : 'Fecha inválida') . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($cedear['ccl_compra'])) . "</td>
                                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($precio_compra_dolares_cedear)) . "</td>
                                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($total_compra_dolares_cedear)) . "</td>
                                            <td>" . ($fecha_venta ? htmlspecialchars(formatearFecha($cedear['fecha_venta_cedear'])) : 'Fecha inválida') . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($cedear['ccl_venta'])) . "</td>
                                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($precio_venta_dolares_cedear)) . "</td>
                                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($total_venta_dolares_cedear)) . "</td>
                                            <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_dolares_cedear, 'u$s') . "</td>
                                            <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_dolares_cedear) . "</td>
                                        </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Completa Cedear Dólares -->

                </div>
                <!-- Fin Cedear Dólares -->

            </div>
        </div>
        <!-- FIN CEDEAR -->

        <hr class="mod">

        <!-- BONOS -->
        <div class="col-12 text-center">
            <div class="container-fluid my-4 efectivo" id="bonos">
                <h5 class="me-2 cartera titulo-botones mb-4">Bonos</h5>

                <!-- Botones -->
                <div class="text-start">
                    <div class="btn-group mb-3" role="group">
                        <button id="btnBonosPesos" class="btn btn-custom ver active">Posición en Pesos</button>
                        <button id="btnBonosDolares" class="btn btn-custom ver">Posición en Dólares</button>
                    </div>
                </div>
                <!-- Fin Botones -->

                <!-- Bonos Pesos -->
                <div id="tablaBonosPesos">

                    <!-- Consolidada Bonos Pesos -->
                    <h6 class="me-2 cartera posiciones mb-4">Historial Consolidado</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Valor total Compra</th>
                                    <th>Valor total Venta</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_bonos_pesos)); ?></td>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_bonos_pesos)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_bonos_pesos); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_bonos_pesos); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Bonos Pesos -->

                    <hr class="linea-accion">

                    <!-- Completa Bonos Pesos -->
                    <h6 class="me-2 cartera posiciones mb-4">Historial Completo</h6>
                    <div class="table-responsive">
                        <table id="completa_bonos_pesos" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align: text-top;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Cantidad</th>
                                    <th colspan="3">Compra</th>
                                    <th colspan="3">Venta</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rentabilidad</th>
                                </tr>
                                <tr>
                                    <th>Fecha<!-- Compra --></th>
                                    <th>Precio<!-- Compra --></th>
                                    <th>Total<!-- Compra --></th>
                                    <th>Fecha<!-- Venta --></th>
                                    <th>Precio<!-- Venta --></th>
                                    <th>Total<!-- Venta --></th>
                                </tr>
                            </thead>
                            <tbody id="tabla-bonos-pesos">
                                <?php
                                foreach ($historial_bonos as $bonos) {
                                    $total_compra_pesos_bonos = $bonos['cantidad_bonos'] * $bonos['precio_compra_bonos'];
                                    $total_venta_pesos_bonos = $bonos['cantidad_bonos'] * $bonos['precio_venta_bonos'];
                                    $rendimiento_pesos_bonos = $total_venta_pesos_bonos - $total_compra_pesos_bonos;
                                    if ($total_compra_pesos_bonos != 0) {
                                        $rentabilidad_pesos_bonos = ($rendimiento_pesos_bonos / $total_compra_pesos_bonos) * 100;
                                    } else {
                                        $rentabilidad_pesos_bonos = 0;
                                    }

                                    // Validar si fecha_compra y fecha_venta son fechas válidas antes de formatear
                                    $fecha_compra = DateTime::createFromFormat('Y-m-d', $bonos['fecha_compra_bonos']);
                                    $fecha_venta = DateTime::createFromFormat('Y-m-d', $bonos['fecha_venta_bonos']);

                                    echo "<tr data-ticker='{$bonos['ticker_bonos']}'>
                                            <td>{$bonos['ticker_bonos']}</td>
                                            <td class='text-right'>" . htmlspecialchars(formatear_numero($bonos['cantidad_bonos'])) . "</td>
                                            <td>" . ($fecha_compra ? htmlspecialchars(formatearFecha($bonos['fecha_compra_bonos'])) : 'Fecha inválida') . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($bonos['precio_compra_bonos'])) . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($total_compra_pesos_bonos)) . "</td>
                                            <td>" . ($fecha_venta ? htmlspecialchars(formatearFecha($bonos['fecha_venta_bonos'])) : 'Fecha inválida') . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($bonos['precio_venta_bonos'])) . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($total_venta_pesos_bonos)) . "</td>
                                            <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_pesos_bonos) . "</td>
                                            <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_pesos_bonos) . "</td>
                                        </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Completa Bonos Pesos -->

                </div>
                <!-- Fin Bonos Pesos -->

                <!-- Bonos Dólares -->
                <div id="tablaBonosDolares" class="d-none">

                    <!-- Consolidada Bonos Dólares -->
                    <h6 class="me-2 cartera posiciones mb-4">Historial Consolidado</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Valor total Compra</th>
                                    <th>Valor total Venta</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_bonos_dolares)); ?></td>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_bonos_dolares)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_bonos_dolares, 'u$s'); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_bonos_dolares); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Bonos Dólares -->

                    <hr class="linea-accion">

                    <!-- Completa Bonos Dólares -->
                    <h6 class="me-2 cartera posiciones mb-4">Historial Completo</h6>
                    <div class="table-responsive">
                        <table id="completa_bonos_dolares" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align: text-top;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Cantidad</th>
                                    <th colspan="4">Compra</th>
                                    <th colspan="4">Venta</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rentabilidad</th>
                                </tr>
                                <tr>
                                    <th>Fecha<!-- Compra --></th>
                                    <th>Dolar CCL<!-- Compra --></th>
                                    <th>Precio<!-- Compra --></th>
                                    <th>Total<!-- Compra --></th>
                                    <th>Fecha<!-- Venta --></th>
                                    <th>Dolar CCL<!-- Venta --></th>
                                    <th>Precio<!-- Venta --></th>
                                    <th>Total<!-- Venta --></th>
                                </tr>
                            </thead>
                            <tbody id="tabla-bonos-dolares">
                                <?php
                                foreach ($historial_bonos as $bonos) {
                                    $precio_compra_dolares_bonos = $bonos['precio_compra_bonos'] / $bonos['ccl_compra'];
                                    $total_compra_dolares_bonos = $bonos['cantidad_bonos'] * $precio_compra_dolares_bonos;
                                    $precio_venta_dolares_bonos = $bonos['precio_venta_bonos'] / $bonos['ccl_venta'];
                                    $total_venta_dolares_bonos = $bonos['cantidad_bonos'] * $precio_venta_dolares_bonos;
                                    $rendimiento_dolares_bonos = $total_venta_dolares_bonos - $total_compra_dolares_bonos;
                                    if ($total_compra_dolares_bonos != 0) {
                                        $rentabilidad_dolares_bonos = ($rendimiento_dolares_bonos / $total_compra_dolares_bonos) * 100;
                                    } else {
                                        $rentabilidad_dolares_bonos = 0;
                                    }

                                    // Validar si fecha_compra y fecha_venta son fechas válidas antes de formatear
                                    $fecha_compra = DateTime::createFromFormat('Y-m-d', $bonos['fecha_compra_bonos']);
                                    $fecha_venta = DateTime::createFromFormat('Y-m-d', $bonos['fecha_venta_bonos']);

                                    echo "<tr data-ticker='{$bonos['ticker_bonos']}'>
                                            <td>{$bonos['ticker_bonos']}</td>
                                            <td class='text-right'>" . htmlspecialchars(formatear_numero($bonos['cantidad_bonos'])) . "</td>
                                            <td>" . ($fecha_compra ? htmlspecialchars(formatearFecha($bonos['fecha_compra_bonos'])) : 'Fecha inválida') . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($bonos['ccl_compra'])) . "</td>
                                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($precio_compra_dolares_bonos)) . "</td>
                                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($total_compra_dolares_bonos)) . "</td>
                                            <td>" . ($fecha_venta ? htmlspecialchars(formatearFecha($bonos['fecha_venta_bonos'])) : 'Fecha inválida') . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($bonos['ccl_venta'])) . "</td>
                                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($precio_venta_dolares_bonos)) . "</td>
                                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($total_venta_dolares_bonos)) . "</td>
                                            <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_dolares_bonos, 'u$s') . "</td>
                                            <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_dolares_bonos) . "</td>
                                        </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Completa Bonos Dólares -->

                </div>
                <!-- Fin Bonos Dólares -->

            </div>
        </div>
        <!-- FIN BONOS -->

        <hr class="mod">

        <!-- FONDOS -->
        <div class="col-12 text-center">
            <div class="container-fluid my-4 efectivo" id="fondos">
                <h5 class="me-2 cartera titulo-botones mb-4">Fondos</h5>

                <!-- Botones -->
                <div class="text-start">
                    <div class="btn-group mb-3" role="group">
                        <button id="btnFondosPesos" class="btn btn-custom ver active">Posición en Pesos</button>
                        <button id="btnFondosDolares" class="btn btn-custom ver">Posición en Dólares</button>
                    </div>
                </div>
                <!-- Fin Botones -->

                <!-- Fondos Pesos -->
                <div id="tablaFondosPesos">

                    <!-- Consolidada Fondos Pesos -->
                    <h6 class="me-2 cartera posiciones mb-4">Historial Consolidado</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Valor total Compra</th>
                                    <th>Valor total Venta</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_fondos_pesos)); ?></td>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_fondos_pesos)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_fondos_pesos); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_fondos_pesos); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Fondos Pesos -->

                    <hr class="linea-accion">

                    <!-- Completa Fondos Pesos -->
                    <h6 class="me-2 cartera posiciones mb-4">Historial Completo</h6>
                    <div class="table-responsive">
                        <table id="completa_fondos_pesos" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align: text-top;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Cantidad</th>
                                    <th colspan="3">Compra</th>
                                    <th colspan="3">Venta</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rentabilidad</th>
                                </tr>
                                <tr>
                                    <th>Fecha<!-- Compra --></th>
                                    <th>Precio<!-- Compra --></th>
                                    <th>Total<!-- Compra --></th>
                                    <th>Fecha<!-- Venta --></th>
                                    <th>Precio<!-- Venta --></th>
                                    <th>Total<!-- Venta --></th>
                                </tr>
                            </thead>
                            <tbody id="tabla-fondos-pesos">
                                <?php
                                foreach ($historial_fondos as $fondos) {
                                    $total_compra_pesos_fondos = $fondos['cantidad_fondos'] * $fondos['precio_compra_fondos'];
                                    $total_venta_pesos_fondos = $fondos['cantidad_fondos'] * $fondos['precio_venta_fondos'];
                                    $rendimiento_pesos_fondos = $total_venta_pesos_fondos - $total_compra_pesos_fondos;
                                    if ($total_compra_pesos_fondos != 0) {
                                        $rentabilidad_pesos_fondos = ($rendimiento_pesos_fondos / $total_compra_pesos_fondos) * 100;
                                    } else {
                                        $rentabilidad_pesos_fondos = 0;
                                    }

                                    // Validar si fecha_compra y fecha_venta son fechas válidas antes de formatear
                                    $fecha_compra = DateTime::createFromFormat('Y-m-d', $fondos['fecha_compra_fondos']);
                                    $fecha_venta = DateTime::createFromFormat('Y-m-d', $fondos['fecha_venta_fondos']);

                                    echo "<tr data-ticker='{$fondos['ticker_fondos']}'>
                                            <td>{$fondos['ticker_fondos']}</td>
                                            <td class='text-right'>" . htmlspecialchars(formatear_numero($fondos['cantidad_fondos'])) . "</td>
                                            <td>" . ($fecha_compra ? htmlspecialchars(formatearFecha($fondos['fecha_compra_fondos'])) : 'Fecha inválida') . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($fondos['precio_compra_fondos'])) . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($total_compra_pesos_fondos)) . "</td>
                                            <td>" . ($fecha_venta ? htmlspecialchars(formatearFecha($fondos['fecha_venta_fondos'])) : 'Fecha inválida') . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($fondos['precio_venta_fondos'])) . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($total_venta_pesos_fondos)) . "</td>
                                            <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_pesos_fondos) . "</td>
                                            <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_pesos_fondos) . "</td>
                                        </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Completa Fondos Pesos -->

                </div>
                <!-- Fin Fondos Pesos -->

                <!-- Fondos Dólares -->
                <div id="tablaFondosDolares" class="d-none">

                    <!-- Consolidada Fondos Dólares -->
                    <h6 class="me-2 cartera posiciones mb-4">Historial Consolidado</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Valor total Compra</th>
                                    <th>Valor total Venta</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_fondos_dolares)); ?></td>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_fondos_dolares)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_fondos_dolares, 'u$s'); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_fondos_dolares); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Fondos Dólares -->

                    <hr class="linea-accion">

                    <!-- Completa Fondos Dólares -->
                    <h6 class="me-2 cartera posiciones mb-4">Historial Completo</h6>
                    <div class="table-responsive">
                        <table id="completa_fondos_dolares" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align: text-top;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Cantidad</th>
                                    <th colspan="4">Compra</th>
                                    <th colspan="4">Venta</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: text-top;">Rentabilidad</th>
                                </tr>
                                <tr>
                                    <th>Fecha<!-- Compra --></th>
                                    <th>Dolar CCL<!-- Compra --></th>
                                    <th>Precio<!-- Compra --></th>
                                    <th>Total<!-- Compra --></th>
                                    <th>Fecha<!-- Venta --></th>
                                    <th>Dolar CCL<!-- Venta --></th>
                                    <th>Precio<!-- Venta --></th>
                                    <th>Total<!-- Venta --></th>
                                </tr>
                            </thead>
                            <tbody id="tabla-fondos-dolares">
                                <?php
                                foreach ($historial_fondos as $fondos) {
                                    $precio_compra_dolares_fondos = $fondos['precio_compra_fondos'] / $fondos['ccl_compra'];
                                    $total_compra_dolares_fondos = $fondos['cantidad_fondos'] * $precio_compra_dolares_fondos;
                                    $precio_venta_dolares_fondos = $fondos['precio_venta_fondos'] / $fondos['ccl_venta'];
                                    $total_venta_dolares_fondos = $fondos['cantidad_fondos'] * $precio_venta_dolares_fondos;
                                    $rendimiento_dolares_fondos = $total_venta_dolares_fondos - $total_compra_dolares_fondos;
                                    if ($total_compra_dolares_fondos != 0) {
                                        $rentabilidad_dolares_fondos = ($rendimiento_dolares_fondos / $total_compra_dolares_fondos) * 100;
                                    } else {
                                        $rentabilidad_dolares_fondos = 0;
                                    }

                                    // Validar si fecha_compra y fecha_venta son fechas válidas antes de formatear
                                    $fecha_compra = DateTime::createFromFormat('Y-m-d', $fondos['fecha_compra_fondos']);
                                    $fecha_venta = DateTime::createFromFormat('Y-m-d', $fondos['fecha_venta_fondos']);

                                    echo "<tr data-ticker='{$fondos['ticker_fondos']}'>
                                            <td>{$fondos['ticker_fondos']}</td>
                                            <td class='text-right'>" . htmlspecialchars(formatear_numero($fondos['cantidad_fondos'])) . "</td>
                                            <td>" . ($fecha_compra ? htmlspecialchars(formatearFecha($fondos['fecha_compra_fondos'])) : 'Fecha inválida') . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($fondos['ccl_compra'])) . "</td>
                                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($precio_compra_dolares_fondos)) . "</td>
                                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($total_compra_dolares_fondos)) . "</td>
                                            <td>" . ($fecha_venta ? htmlspecialchars(formatearFecha($fondos['fecha_venta_fondos'])) : 'Fecha inválida') . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($fondos['ccl_venta'])) . "</td>
                                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($precio_venta_dolares_fondos)) . "</td>
                                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($total_venta_dolares_fondos)) . "</td>
                                            <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_dolares_fondos, 'u$s') . "</td>
                                            <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_dolares_fondos) . "</td>
                                        </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Completa Fondos Dólares -->

                </div>
                <!-- Fin Fondos Dólares -->

            </div>
        </div>
        <!-- FIN FONDOS -->

        <hr class="mod" style="margin-bottom: 80px;">

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="../js/tooltip.js"></script>
    <script src="../js/botones_pesos_dolares.js"></script>
    <script src="../js/filtro_tablas.js"></script>
    <script src="../js/preloader.js"></script>
    <script src="../js/fixedImage.js"></script>
    <!-- FIN JS -->

</body>

</html>