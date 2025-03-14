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
                                    <th>Valor total Compra</th>
                                    <th>Valor total Venta</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $valor_compra_consolidado_acciones_pesos = 0;
                                $valor_venta_consolidado_acciones_pesos = 0;
                                foreach ($historial_acciones as $accion) {
                                    $total_compra_pesos_accion = $accion['cantidad'] * $accion['precio_compra'];
                                    $total_venta_pesos_accion = $accion['cantidad'] * $accion['precio_venta'];
                                    $valor_compra_consolidado_acciones_pesos += $total_compra_pesos_accion;
                                    $valor_venta_consolidado_acciones_pesos += $total_venta_pesos_accion;
                                }

                                $rendimiento_consolidado_acciones_pesos = $valor_venta_consolidado_acciones_pesos - $valor_compra_consolidado_acciones_pesos;
                                if ($valor_compra_consolidado_acciones_pesos != 0) {
                                    $rentabilidad_consolidado_acciones_pesos = ($rendimiento_consolidado_acciones_pesos / $valor_compra_consolidado_acciones_pesos) * 100;
                                } else {
                                    $rentabilidad_consolidado_acciones_pesos = 0;
                                }
                                ?>
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
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

                                    echo "<tr data-ticker='{$accion['ticker']}'>
                                            <td>{$accion['ticker']}</td>
                                            <td>{$accion['cantidad']}</td>
                                            <td>" . htmlspecialchars(formatearFecha($accion['fecha_compra'])) . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($accion['precio_compra'])) . "</td>
                                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($total_compra_pesos_accion)) . "</td>
                                            <td>" . htmlspecialchars(formatearFecha($accion['fecha_venta'])) . "</td>
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
                                <?php
                                $valor_compra_consolidado_acciones_dolares = 0;
                                $valor_venta_consolidado_acciones_dolares = 0;
                                foreach ($historial_acciones as $accion) {
                                    $precio_compra_dolares_accion = $accion['precio_compra'] / $accion['ccl_compra'];
                                    $total_compra_dolares_accion = $accion['cantidad'] * $precio_compra_dolares_accion;
                                    $precio_venta_dolares_accion = $accion['precio_venta'] / $accion['ccl_venta'];
                                    $total_venta_dolares_accion = $accion['cantidad'] * $precio_venta_dolares_accion;
                                    $valor_compra_consolidado_acciones_dolares += $total_compra_dolares_accion;
                                    $valor_venta_consolidado_acciones_dolares += $total_venta_dolares_accion;
                                }
                                $rendimiento_consolidado_acciones_dolares = $valor_venta_consolidado_acciones_dolares - $valor_compra_consolidado_acciones_dolares;
                                if ($valor_compra_consolidado_acciones_dolares != 0) {
                                    $rentabilidad_consolidado_acciones_dolares = ($rendimiento_consolidado_acciones_dolares / $valor_compra_consolidado_acciones_dolares) * 100;
                                } else {
                                    $rentabilidad_consolidado_acciones_dolares = 0;
                                }
                                ?>
                                <tr>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_acciones_dolares)); ?></td>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_acciones_dolares)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_acciones_dolares); ?></td>
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

                                    echo "<tr data-ticker='{$accion['ticker']}'>
                                    <td>{$accion['ticker']}</td>
                                    <td>{$accion['cantidad']}</td>

                                    <td>" . htmlspecialchars(formatearFecha($accion['fecha_compra'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($accion['ccl_compra'])) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($precio_compra_dolares_accion)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($total_compra_dolares_accion)) . "</td>

                                    <td>" . htmlspecialchars(formatearFecha($accion['fecha_venta'])) . "</td>
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
                                <?php
                                $valor_compra_consolidado_cedear_pesos = 0;
                                $valor_venta_consolidado_cedear_pesos = 0;
                                foreach ($historial_cedear as $cedear) {
                                    $total_compra_pesos_cedear = $cedear['cantidad_cedear'] * $cedear['precio_compra_cedear'];
                                    $total_venta_pesos_cedear = $cedear['cantidad_cedear'] * $cedear['precio_venta_cedear'];
                                    $valor_compra_consolidado_cedear_pesos += $total_compra_pesos_cedear;
                                    $valor_venta_consolidado_cedear_pesos += $total_venta_pesos_cedear;
                                }

                                $rendimiento_consolidado_cedear_pesos = $valor_venta_consolidado_cedear_pesos - $valor_compra_consolidado_cedear_pesos;
                                if ($valor_compra_consolidado_cedear_pesos != 0) {
                                    $rentabilidad_consolidado_cedear_pesos = ($rendimiento_consolidado_cedear_pesos / $valor_compra_consolidado_cedear_pesos) * 100;
                                } else {
                                    $rentabilidad_consolidado_cedear_pesos = 0;
                                }
                                ?>
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
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

                                    echo "<tr data-ticker='{$cedear['ticker_cedear']}'>
                                    <td>{$cedear['ticker_cedear']}</td>
                                    <td>{$cedear['cantidad_cedear']}</td>
                                    <td>" . htmlspecialchars(formatearFecha($cedear['fecha_compra_cedear'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($cedear['precio_compra_cedear'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($total_compra_pesos_cedear)) . "</td>
                                    <td>" . htmlspecialchars(formatearFecha($cedear['fecha_venta_cedear'])) . "</td>
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
                                <?php
                                $valor_compra_consolidado_cedear_dolares = 0;
                                $valor_venta_consolidado_cedear_dolares = 0;
                                foreach ($historial_cedear as $cedear) {
                                    $precio_compra_dolares_cedear = $cedear['precio_compra_cedear'] / $cedear['ccl_compra'];
                                    $total_compra_dolares_cedear = $cedear['cantidad_cedear'] * $precio_compra_dolares_cedear;
                                    $precio_venta_dolares_cedear = $cedear['precio_venta_cedear'] / $cedear['ccl_venta'];
                                    $total_venta_dolares_cedear = $cedear['cantidad_cedear'] * $precio_venta_dolares_cedear;
                                    $valor_compra_consolidado_cedear_dolares += $total_compra_dolares_cedear;
                                    $valor_venta_consolidado_cedear_dolares += $total_venta_dolares_cedear;
                                }
                                $rendimiento_consolidado_cedear_dolares = $valor_venta_consolidado_cedear_dolares - $valor_compra_consolidado_cedear_dolares;
                                if ($valor_compra_consolidado_cedear_dolares != 0) {
                                    $rentabilidad_consolidado_cedear_dolares = ($rendimiento_consolidado_cedear_dolares / $valor_compra_consolidado_cedear_dolares) * 100;
                                } else {
                                    $rentabilidad_consolidado_cedear_dolares = 0;
                                }
                                ?>
                                <tr>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_compra_consolidado_cedear_dolares)); ?></td>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_venta_consolidado_cedear_dolares)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_cedear_dolares); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_cedear_dolares); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Cedear Dólares -->

                    <hr class="linea-accion">

                    <!-- Completa Cedear Dólares -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
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

                                    echo "<tr data-ticker='{$cedear['ticker_cedear']}'>
                            <td>{$cedear['ticker_cedear']}</td>
                            <td>{$cedear['cantidad_cedear']}</td>

                            <td>" . htmlspecialchars(formatearFecha($cedear['fecha_compra_cedear'])) . "</td>
                            <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($cedear['ccl_compra'])) . "</td>
                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($precio_compra_dolares_cedear)) . "</td>
                            <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($total_compra_dolares_cedear)) . "</td>

                            <td>" . htmlspecialchars(formatearFecha($cedear['fecha_venta_cedear'])) . "</td>
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

            </div>
        </div>
        <!-- FIN CEDEAR -->

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
    <!-- FIN JS -->
</body>

</html>