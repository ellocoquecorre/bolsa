<?php
require_once '../../config/config.php';

if (!isset($conexion)) {
    die("Error: No se pudo establecer conexión con la base de datos.");
}

$cliente_id = isset($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : 1;

// Obtener nombre y apellido
try {
    $stmt = $conexion->prepare("SELECT nombre, apellido FROM clientes WHERE cliente_id = ?");
    $stmt->execute([$cliente_id]);
    $cliente = $stmt->fetch();

    $nombre = $cliente['nombre'] ?? "Cliente";
    $apellido = $cliente['apellido'] ?? "Desconocido";
} catch (PDOException $e) {
    error_log("Error al obtener nombre del cliente: " . $e->getMessage());
    $nombre = "Cliente";
    $apellido = "Desconocido";
}

// Incluir funciones
require_once '../funciones/cliente_funciones.php';
require_once '../funciones/formato_dinero.php';

// Obtener saldo en pesos y formatearlo
$saldo_en_pesos = obtenerSaldoPesos($cliente_id);
$saldo_en_pesos_formateado = formatear_dinero($saldo_en_pesos);

// Definir promedio_ccl si no está
if (!isset($promedio_ccl)) {
    $promedio_ccl = 1;
}

// Obtener saldo en dólares y formatearlo
$saldo_en_dolares = obtenerSaldoDolares($cliente_id, $promedio_ccl);
$saldo_en_dolares_formateado = formatear_dinero($saldo_en_dolares);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goodfellas Inc.</title>
    <meta name="cliente_id" content="<?php echo $cliente_id; ?>">
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <!-- FIN CSS -->
</head>

<body>

    <!-- PRELOADER -->
    <div class="preloader d-flex justify-content-center align-items-center" id="preloader">
        <div class="preloader-content">
            <img src="../img/preloader.gif" alt="Preloader" class="preloader-img img-fluid">
        </div>
    </div>
    <!-- FIN PRELOADER -->

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
            <h4 class="fancy"><?php echo htmlspecialchars($nombre . ' ' . $apellido); ?></h4>
            <a href="historial.php?cliente_id=<?php echo $cliente_id; ?>" class="btn btn-custom ver">
                <i class="fa-solid fa-hourglass me-2"></i>Historial</a>
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <!-- TOTAL -->
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

                    <!-- Consolidada Pesos -->
                    <h6 class="me-2 cartera posiciones mb-4">Posición Consolidada</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Valor Total Inicial</th>
                                    <th>Valor Total Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $valor_inicial_consolidado_resumen_pesos = $valor_inicial_consolidado_acciones_pesos + $valor_inicial_consolidado_cedear_pesos + $valor_inicial_consolidado_bonos_pesos + $valor_inicial_consolidado_fondos_pesos;
                                $valor_actual_consolidado_resumen_pesos = $valor_actual_consolidado_acciones_pesos + $valor_actual_consolidado_cedear_pesos + $valor_actual_consolidado_bonos_pesos + $valor_actual_consolidado_fondos_pesos;
                                $rendimiento_consolidado_resumen_pesos = $valor_actual_consolidado_resumen_pesos - $valor_inicial_consolidado_resumen_pesos;
                                $rentabilidad_consolidado_resumen_pesos = ($valor_inicial_consolidado_resumen_pesos != 0) ? (($valor_actual_consolidado_resumen_pesos - $valor_inicial_consolidado_resumen_pesos) / $valor_inicial_consolidado_resumen_pesos) * 100 : 0;
                                ?>
                                <tr>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_resumen_pesos)); ?></td>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_resumen_pesos)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_resumen_pesos); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_resumen_pesos); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Pesos -->

                    <hr class="linea-accion">

                    <!-- Detalle Pesos -->
                    <div class="row">
                        <h6 class="me-2 cartera posiciones mb-4">Posición Detallada</h6>
                        <div class="col-lg-6 col-md-12 table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-secondary text-white">
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
                                        <td class='text-right'>$ <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_acciones_pesos)); ?></td>
                                        <td class='text-right'>$ <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_acciones_pesos)); ?></td>
                                        <td class='text-right'><?php echo formatear_y_colorear_valor($rendimiento_consolidado_acciones_pesos); ?></td>
                                        <td class='text-right'><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_acciones_pesos); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cedears</strong></td>
                                        <td class='text-right'>$ <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_cedear_pesos)); ?></td>
                                        <td class='text-right'>$ <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_cedear_pesos)); ?></td>
                                        <td class='text-right'><?php echo formatear_y_colorear_valor($rendimiento_consolidado_cedear_pesos); ?></td>
                                        <td class='text-right'><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_cedear_pesos); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Bonos</strong></td>
                                        <td class='text-right'>$ <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_bonos_pesos)); ?></td>
                                        <td class='text-right'>$ <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_bonos_pesos)); ?></td>
                                        <td class='text-right'><?php echo formatear_y_colorear_valor($rendimiento_consolidado_bonos_pesos); ?></td>
                                        <td class='text-right'><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_bonos_pesos); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fondos</strong></td>
                                        <td class='text-right'>$ <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_fondos_pesos)); ?></td>
                                        <td class='text-right'>$ <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_fondos_pesos)); ?></td>
                                        <td class='text-right'><?php echo formatear_y_colorear_valor($rendimiento_consolidado_fondos_pesos); ?></td>
                                        <td class='text-right'><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_fondos_pesos); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6 col-md-12 table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-secondary text-white">
                                    <tr>
                                        <th colspan="2">Efectivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width: 50%;">Saldo en pesos: </td>
                                        <td style="width: 50%;">$ <?php echo $saldo_en_pesos_formateado; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%;">Promedio dólar CCL: </td>
                                        <td style="width: 50%;">$ <?php echo (formatear_dinero($promedio_ccl)); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%;">Saldo en dólares: </td>
                                        <td style="width: 50%;">u$s <?php echo $saldo_en_dolares_formateado; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Fin Detalle Pesos -->

                    <hr class="linea-accion">

                    <!-- Gráficos Pesos -->
                    <div class="row">
                        <!-- Activos Pesos -->
                        <div class="col-lg-4 col-md-12">
                            <h6 class="me-2 cartera posiciones mb-4">Distribución de activos</h6>
                            <div class="chart-container" style="position: relative; height:50vh; width:100%;">
                                <div style="display: flex; justify-content: center; align-items: center; height: 100%;">
                                    <canvas id="ChartPesos"></canvas>
                                </div>
                                <!-- Agregar datos ocultos para el gráfico -->
                                <span id="valor_actual_consolidado_acciones_pesos" style="display: none;"><?php echo $valor_actual_consolidado_acciones_pesos; ?></span>
                                <span id="valor_actual_consolidado_cedear_pesos" style="display: none;"><?php echo $valor_actual_consolidado_cedear_pesos; ?></span>
                                <span id="valor_actual_consolidado_bonos_pesos" style="display: none;"><?php echo $valor_actual_consolidado_bonos_pesos; ?></span>
                                <span id="valor_actual_consolidado_fondos_pesos" style="display: none;"><?php echo $valor_actual_consolidado_fondos_pesos; ?></span>
                                <span id="saldo_en_pesos" style="display: none;"><?php echo $saldo_en_pesos; ?></span>
                            </div>
                        </div>
                        <!-- Fin Activos Pesos -->
                        <!-- Rendimiento Pesos -->
                        <div class="col-lg-4 col-md-12">
                            <h6 class="me-2 cartera posiciones mb-4">Rendimiento en Pesos</h6>
                            <div class="chart-container" style="position: relative; height:50vh; width:100%;">
                                <div style="display: flex; justify-content: center; align-items: center; height: 100%;">
                                    <canvas id="ChartRendimientoPesos"></canvas>
                                </div>
                                <!-- Agregar datos ocultos para el gráfico -->
                                <span id="rendimiento_consolidado_acciones_pesos" style="display: none;"><?php echo $rendimiento_consolidado_acciones_pesos; ?></span>
                                <span id="rendimiento_consolidado_cedear_pesos" style="display: none;"><?php echo $rendimiento_consolidado_cedear_pesos; ?></span>
                                <span id="rendimiento_consolidado_bonos_pesos" style="display: none;"><?php echo $rendimiento_consolidado_bonos_pesos; ?></span>
                                <span id="rendimiento_consolidado_fondos_pesos" style="display: none;"><?php echo $rendimiento_consolidado_fondos_pesos; ?></span>
                            </div>
                        </div>
                        <!-- Fin Rendimiento Pesos -->
                        <!-- Rentabilidad Pesos -->
                        <div class="col-lg-4 col-md-12">
                            <h6 class="me-2 cartera posiciones mb-4">Rentabilidad en Pesos</h6>
                            <div class="chart-container" style="position: relative; height:50vh; width:100%;">
                                <div style="display: flex; justify-content: center; align-items: center; height: 100%;">
                                    <canvas id="ChartRentPesos"></canvas>
                                </div>
                                <!-- Datos ocultos para la rentabilidad en pesos -->
                                <span id="rentabilidad_consolidado_acciones_pesos" style="display: none;"><?php echo $rentabilidad_consolidado_acciones_pesos; ?></span>
                                <span id="rentabilidad_consolidado_cedear_pesos" style="display: none;"><?php echo $rentabilidad_consolidado_cedear_pesos; ?></span>
                                <span id="rentabilidad_consolidado_bonos_pesos" style="display: none;"><?php echo $rentabilidad_consolidado_bonos_pesos; ?></span>
                                <span id="rentabilidad_consolidado_fondos_pesos" style="display: none;"><?php echo $rentabilidad_consolidado_fondos_pesos; ?></span>
                            </div>
                        </div>
                        <!-- Fin Rentabilidad Pesos -->
                    </div>
                    <!-- Fin Gráficos Pesos -->

                </div>
                <!-- Fin Pesos -->

                <!-- Dolares -->
                <div id="tablaResumenDolares" class="d-none">

                    <!-- Consolidada Dolares -->
                    <h6 class="me-2 cartera posiciones mb-4">Posición Consolidada</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Valor Total Inicial</th>
                                    <th>Valor Total Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $valor_inicial_consolidado_resumen_dolares = $valor_inicial_consolidado_acciones_dolares + $valor_inicial_consolidado_cedear_dolares + $valor_inicial_consolidado_bonos_dolares + $valor_inicial_consolidado_fondos_dolares;
                                $valor_actual_consolidado_resumen_dolares = $valor_actual_consolidado_acciones_dolares + $valor_actual_consolidado_cedear_dolares + $valor_actual_consolidado_bonos_dolares + $valor_actual_consolidado_fondos_dolares;
                                $rendimiento_consolidado_resumen_dolares = $valor_actual_consolidado_resumen_dolares - $valor_inicial_consolidado_resumen_dolares;
                                $rentabilidad_consolidado_resumen_dolares = ($valor_inicial_consolidado_resumen_dolares != 0) ? (($valor_actual_consolidado_resumen_dolares - $valor_inicial_consolidado_resumen_dolares) / $valor_inicial_consolidado_resumen_dolares) * 100 : 0;
                                ?>
                                <tr>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_resumen_dolares)); ?></td>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_resumen_dolares)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_resumen_dolares, 'u$s'); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_resumen_dolares); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Dolares -->

                    <hr class="linea-accion">

                    <!-- Detalle Dolares -->
                    <div class="row">
                        <h6 class="me-2 cartera posiciones mb-4">Posición Detallada</h6>
                        <div class="col-lg-6 col-md-12 table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-secondary text-white">
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
                                        <td class='text-right'>u$S <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_acciones_dolares)); ?></td>
                                        <td class='text-right'>u$s <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_acciones_dolares)); ?></td>
                                        <td class='text-right'><?php echo formatear_y_colorear_valor($rendimiento_consolidado_acciones_dolares, 'u$s'); ?></td>
                                        <td class='text-right'><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_acciones_dolares); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cedears</strong></td>
                                        <td class='text-right'>u$s <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_cedear_dolares)); ?></td>
                                        <td class='text-right'>u$s <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_cedear_dolares)); ?></td>
                                        <td class='text-right'><?php echo formatear_y_colorear_valor($rendimiento_consolidado_cedear_dolares, 'u$s'); ?></td>
                                        <td class='text-right'><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_cedear_dolares); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Bonos</strong></td>
                                        <td class='text-right'>u$s <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_bonos_dolares)); ?></td>
                                        <td class='text-right'>u$s <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_bonos_dolares)); ?></td>
                                        <td class='text-right'><?php echo formatear_y_colorear_valor($rendimiento_consolidado_bonos_dolares, 'u$s'); ?></td>
                                        <td class='text-right'><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_bonos_dolares); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fondos</strong></td>
                                        <td class='text-right'>u$s <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_fondos_dolares)); ?></td>
                                        <td class='text-right'>u$s <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_fondos_dolares)); ?></td>
                                        <td class='text-right'><?php echo formatear_y_colorear_valor($rendimiento_consolidado_fondos_dolares, 'u$s'); ?></td>
                                        <td class='text-right'><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_fondos_dolares); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6 col-md-12 table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-secondary text-white">
                                    <tr>
                                        <th colspan="2">Efectivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width: 50%;">Saldo en pesos: </td>
                                        <td style="width: 50%;">$ <?php echo $saldo_en_pesos_formateado; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%;">Promedio dólar CCL: </td>
                                        <td style="width: 50%;">$ <?php echo (formatear_dinero($promedio_ccl)); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%;">Saldo en dólares: </td>
                                        <td style="width: 50%;">u$s <?php echo $saldo_en_dolares_formateado; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Fin Detalle Dolares -->

                    <hr class="linea-accion">

                    <!-- Gráficos Dolares -->
                    <div class="row">
                        <!-- Activos Dólares -->
                        <div class="col-lg-4 col-md-12">
                            <h6 class="me-2 cartera posiciones mb-4">Distribución de activos</h6>
                            <div class="chart-container" style="position: relative; height:50vh; width:100%;">
                                <div style="display: flex; justify-content: center; align-items: center; height: 100%;">
                                    <canvas id="ChartDolares"></canvas>
                                </div>
                                <!-- Agregar datos ocultos para el gráfico -->
                                <span id="valor_actual_consolidado_acciones_dolares" style="display: none;"><?php echo $valor_actual_consolidado_acciones_dolares; ?></span>
                                <span id="valor_actual_consolidado_cedear_dolares" style="display: none;"><?php echo $valor_actual_consolidado_cedear_dolares; ?></span>
                                <span id="valor_actual_consolidado_bonos_dolares" style="display: none;"><?php echo $valor_actual_consolidado_bonos_dolares; ?></span>
                                <span id="valor_actual_consolidado_fondos_dolares" style="display: none;"><?php echo $valor_actual_consolidado_fondos_dolares; ?></span>
                                <span id="saldo_en_dolares" style="display: none;"><?php echo $saldo_en_dolares; ?></span>
                            </div>
                        </div>
                        <!-- Fin Activos Dólares -->
                        <!-- Rendimiento Dólares -->
                        <div class="col-lg-4 col-md-12">
                            <h6 class="me-2 cartera posiciones mb-4">Rendimiento en Dólares</h6>
                            <div class="chart-container" style="position: relative; height:50vh; width:100%;">
                                <div style="display: flex; justify-content: center; align-items: center; height: 100%;">
                                    <canvas id="ChartRendimientoDolares"></canvas>
                                </div>
                                <!-- Agregar datos ocultos para el gráfico -->
                                <span id="rendimiento_consolidado_acciones_dolares" style="display: none;"><?php echo $rendimiento_consolidado_acciones_dolares; ?></span>
                                <span id="rendimiento_consolidado_cedear_dolares" style="display: none;"><?php echo $rendimiento_consolidado_cedear_dolares; ?></span>
                                <span id="rendimiento_consolidado_bonos_dolares" style="display: none;"><?php echo $rendimiento_consolidado_bonos_dolares; ?></span>
                                <span id="rendimiento_consolidado_fondos_dolares" style="display: none;"><?php echo $rendimiento_consolidado_fondos_dolares; ?></span>
                            </div>
                        </div>
                        <!-- Fin Rendimiento Dólares -->
                        <!-- Rentabilidad Dólares -->
                        <div class="col-lg-4 col-md-12">
                            <h6 class="me-2 cartera posiciones mb-4">Rentabilidad en Dólares</h6>
                            <div class="chart-container" style="position: relative; height:50vh; width:100%;">
                                <div style="display: flex; justify-content: center; align-items: center; height: 100%;">
                                    <canvas id="ChartRentDolares"></canvas>
                                </div>
                                <!-- Datos ocultos para la rentabilidad en dólares -->
                                <span id="rentabilidad_consolidado_acciones_dolares" style="display: none;"><?php echo $rentabilidad_consolidado_acciones_dolares; ?></span>
                                <span id="rentabilidad_consolidado_cedear_dolares" style="display: none;"><?php echo $rentabilidad_consolidado_cedear_dolares; ?></span>
                                <span id="rentabilidad_consolidado_bonos_dolares" style="display: none;"><?php echo $rentabilidad_consolidado_bonos_dolares; ?></span>
                                <span id="rentabilidad_consolidado_fondos_dolares" style="display: none;"><?php echo $rentabilidad_consolidado_fondos_dolares; ?></span>
                            </div>
                        </div>
                        <!-- Fin Rentabilidad Dólares -->
                    </div>
                    <!-- Fin Gráficos Dolares -->

                </div>
                <!-- Fin Dolares -->

            </div>
        </div>
        <!-- FIN TOTAL -->

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

                <hr class="linea-accion">

                <!-- Acciones Pesos -->
                <div id="tablaAccionesPesos">

                    <!-- Consolidada Acciones Pesos -->
                    <h6 class="me-2 cartera posiciones mb-4">Posición Consolidada</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Valor Total Inicial</th>
                                    <th>Valor Total Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
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
                    <h6 class="me-2 cartera posiciones mb-4">Posición Detallada</h6>
                    <div class="table-responsive">
                        <table id="tenencia_acciones_pesos" class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: middle;">Fecha</th>
                                    <th rowspan="2" class="text-center" style="vertical-align: middle;">Cantidad</th>
                                    <th colspan="2" class="text-center">Valor unitario</th>
                                    <th colspan="2" class="text-center">Valor total</th>
                                    <th rowspan="2" style="vertical-align: middle;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: middle;">Rentabilidad</th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr>
                                    <th class="text-center"><!-- Precio -->Compra</th>
                                    <th class="text-center"><!-- Precio -->Hoy</th>
                                    <th class="text-center"><!-- Valor -->Compra</th>
                                    <th class="text-center"><!-- Valor -->Hoy</th>
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
                                    <td class='text-right'>" . htmlspecialchars(formatear_numero($accion['cantidad'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($accion['precio'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($precio_actual)) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($valor_inicial_acciones_pesos)) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($valor_actual_acciones_pesos)) . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_acciones_pesos) . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_acciones_pesos) . "</td>
                                    <td class='text-center'>
                                        <div class='dropdown d-flex justify-content-center'>
                                            <button class='btn custom-btn dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false' title='Opciones'>
                                                <i class='fa-solid fa-bars'></i>
                                            </button>
                                            <ul class='dropdown-menu dropdown-menu-end' aria-labelledby='dropdownMenuButton'>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/compra_mas_acciones.php?cliente_id={$cliente_id}&ticker={$accion['ticker']}'><i class='fa-solid fa-plus me-2'></i>Comprar mas</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/venta_parcial_acciones.php?cliente_id={$cliente_id}&ticker={$accion['ticker']}'><i class='fa-solid fa-percent me-2'></i>Venta parcial</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/venta_total_acciones.php?cliente_id={$cliente_id}&ticker={$accion['ticker']}'><i class='fa-solid fa-coins me-2'></i>Venta total</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/editar_compra_acciones.php?cliente_id={$cliente_id}&ticker={$accion['ticker']}'><i class='fa-solid fa-edit me-2'></i>Editar</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item eliminar text-danger' href='#' onclick='eliminarAccion(this)'><i class='fa-solid fa-trash me-2'></i>Eliminar</a>
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
                    <h6 class="me-2 cartera posiciones mb-4">Posición Consolidada</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Valor Total Inicial</th>
                                    <th>Valor Total Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
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
                    <h6 class="me-2 cartera posiciones mb-4">Posición Detallada</h6>
                    <div class="table-responsive">
                        <table id="tenencia_acciones_dolares" class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: middle;">Fecha</th>
                                    <th rowspan="2" class="text-center" style="vertical-align: middle;">Cantidad</th>
                                    <th colspan="2" class="text-center">Dólar CCL</th>
                                    <th colspan="2" class="text-center">Valor unitario</th>
                                    <th colspan="2" class="text-center">Valor total</th>
                                    <th rowspan="2" style="vertical-align: middle;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: middle;">Rentabilidad</th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr>
                                    <th class="text-center"><!-- Valor CCL -->Compra</th>
                                    <th class="text-center"><!-- Valor CCL -->Hoy</th>
                                    <th class="text-center"><!-- Precio -->Compra</th>
                                    <th class="text-center"><!-- Precio -->Hoy</th>
                                    <th class="text-center"><!-- X -->Compra</th>
                                    <th class="text-center"><!-- X -->Hoy</th>
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
                                    $valor_compra_ccl = obtenerCCLCompra($cliente_id, $accion['ticker']);
                                    $precio_actual_dolares = $precio_actual / $promedio_ccl;
                                    $valor_inicial_acciones_dolares = $valor_inicial_acciones_pesos / $valor_compra_ccl;
                                    $valor_actual_acciones_dolares = $valor_actual_acciones_pesos / $promedio_ccl;
                                    $rendimiento_acciones_dolares = $valor_actual_acciones_dolares - $valor_inicial_acciones_dolares;
                                    $rentabilidad_acciones_dolares = (($valor_actual_acciones_dolares - $valor_inicial_acciones_dolares) / $valor_inicial_acciones_dolares) * 100;


                                    echo "<tr data-ticker='{$accion['ticker']}'>
                                    <td>{$accion['ticker']}</td>
                                    <td>" . htmlspecialchars(formatearFecha($accion['fecha'])) . "</td>
                                    <td class='text-right'>" . htmlspecialchars(formatear_numero($accion['cantidad'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($valor_compra_ccl)) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($promedio_ccl)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($accion['precio'] / $valor_compra_ccl)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($precio_actual_dolares)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($valor_inicial_acciones_dolares)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($valor_actual_acciones_dolares)) . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_acciones_dolares, 'u$s') . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_acciones_dolares) . "</td>
                                    <td class='text-center'>
                                        <div class='dropdown d-flex justify-content-center'>
                                            <button class='btn custom-btn dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false'>
                                                <i class='fa-solid fa-bars'></i>
                                            </button>
                                            <ul class='dropdown-menu dropdown-menu-end' aria-labelledby='dropdownMenuButton'>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/compra_mas_acciones.php?cliente_id={$cliente_id}&ticker={$accion['ticker']}'><i class='fa-solid fa-plus me-2'></i>Comprar mas</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/venta_parcial_acciones.php?cliente_id={$cliente_id}&ticker={$accion['ticker']}'><i class='fa-solid fa-percent me-2'></i> Venta parcial</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/venta_total_acciones.php?cliente_id={$cliente_id}&ticker={$accion['ticker']}'><i class='fa-solid fa-check-circle me-2'></i> Venta total</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/editar_compra_acciones.php?cliente_id={$cliente_id}&ticker={$accion['ticker']}'><i class='fa-solid fa-pen me-2'></i> Editar</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item eliminar text-danger' href='#' onclick='eliminarAccion(this)'><i class='fa-solid fa-trash me-2'></i>Eliminar</a>
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

        <!-- CEDEAR -->
        <div class="col-12 text-center">
            <div class="container-fluid my-4 efectivo" id="cedears">
                <h5 class="me-2 cartera titulo-botones mb-4">Cedear</h5>

                <!-- Botones -->
                <div class="text-start">
                    <div class="btn-group mb-3" role="group">
                        <button id="btnCedearPesos" class="btn btn-custom ver active">Posición en Pesos</button>
                        <button id="btnCedearDolares" class="btn btn-custom ver">Posición en Dólares</button>
                    </div>
                </div>
                <!-- Fin Botones -->

                <hr class="linea-accion">

                <!-- Cedear Pesos -->
                <div id="tablaCedearPesos">

                    <!-- Consolidada Cedear Pesos -->
                    <h6 class="me-2 cartera posiciones mb-4">Posición Consolidada</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Valor Total Inicial</th>
                                    <th>Valor Total Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_cedear_pesos)); ?></td>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_cedear_pesos)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_cedear_pesos); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_cedear_pesos); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Cedear Pesos -->

                    <hr class="linea-accion">

                    <!-- Completa Cedear Pesos -->
                    <h6 class="me-2 cartera posiciones mb-4">Posición Detallada</h6>
                    <div class="table-responsive">
                        <table id="tenencia_cedear_pesos" class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: middle;">Fecha</th>
                                    <th rowspan="2" class="text-center" style="vertical-align: middle;">Cantidad</th>
                                    <th colspan="2" class="text-center">Valor unitario</th>
                                    <th colspan="2" class="text-center">Valor total</th>
                                    <th rowspan="2" style="vertical-align: middle;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: middle;">Rentabilidad</th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr>
                                    <th class="text-center"><!-- Precio -->Compra</th>
                                    <th class="text-center"><!-- Precio -->Hoy</th>
                                    <th class="text-center"><!-- Valor -->Compra</th>
                                    <th class="text-center"><!-- Valor -->Hoy</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-cedear-pesos">
                                <?php
                                $cedear = obtenerCedear($cliente_id);
                                foreach ($cedear as $c) {
                                    $precio_actual = obtenerPrecioActualCedear($c['ticker_cedear']);
                                    $valor_inicial_cedear_pesos = $c['precio_cedear'] * $c['cantidad_cedear'];
                                    $valor_actual_cedear_pesos = $precio_actual * $c['cantidad_cedear'];
                                    $rendimiento_cedear_pesos = $valor_actual_cedear_pesos - $valor_inicial_cedear_pesos;
                                    $rentabilidad_cedear_pesos = (($rendimiento_cedear_pesos) / $valor_inicial_cedear_pesos) * 100;

                                    echo "<tr data-ticker='{$c['ticker_cedear']}'>
                                    <td>{$c['ticker_cedear']}</td>
                                    <td>" . htmlspecialchars(formatearFechaCedear($c['fecha_cedear'])) . "</td>
                                    <td class='text-right'>" . htmlspecialchars(formatear_numero($c['cantidad_cedear'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($c['precio_cedear'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($precio_actual)) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($valor_inicial_cedear_pesos)) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($valor_actual_cedear_pesos)) . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_cedear_pesos) . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_cedear_pesos) . "</td>
                                    <td class='text-center'>
                                        <div class='dropdown d-flex justify-content-center'>
                                            <button class='btn custom-btn dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false' title='Opciones'>
                                                <i class='fa-solid fa-bars'></i>
                                            </button>
                                            <ul class='dropdown-menu dropdown-menu-end' aria-labelledby='dropdownMenuButton'>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/compra_mas_cedears.php?cliente_id={$cliente_id}&ticker={$c['ticker_cedear']}'><i class='fa-solid fa-plus me-2'></i>Comprar mas</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/venta_parcial_cedears.php?cliente_id={$cliente_id}&ticker={$c['ticker_cedear']}'><i class='fa-solid fa-percent me-2'></i>Venta parcial</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/venta_total_cedears.php?cliente_id={$cliente_id}&ticker={$c['ticker_cedear']}'><i class='fa-solid fa-coins me-2'></i>Venta total</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/editar_compra_cedears.php?cliente_id={$cliente_id}&ticker={$c['ticker_cedear']}'><i class='fa-solid fa-edit me-2'></i>Editar</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item eliminar text-danger' href='#' onclick='eliminarCedear(this)'><i class='fa-solid fa-trash me-2'></i>Eliminar</a>
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
                    <!-- Fin Completa Cedear Pesos -->

                </div>
                <!-- Fin Cedear Pesos -->

                <!-- Cedear Dólares -->
                <div id="tablaCedearDolares" class="d-none">

                    <!-- Consolidada Cedear Dólares -->
                    <h6 class="me-2 cartera posiciones mb-4">Posición Consolidada</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Valor Total Inicial</th>
                                    <th>Valor Total Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_cedear_dolares)); ?></td>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_cedear_dolares)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_cedear_dolares, 'u$s'); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_cedear_dolares); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Cedear Dólares -->

                    <hr class="linea-accion">

                    <!-- Completa Cedear Dólares -->
                    <h6 class="me-2 cartera posiciones mb-4">Posición Detallada</h6>
                    <div class="table-responsive">
                        <table id="tenencia_cedear_dolares" class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: middle;">Fecha</th>
                                    <th rowspan="2" class="text-center" style="vertical-align: middle;">Cantidad</th>
                                    <th colspan="2" class="text-center">Dólar CCL</th>
                                    <th colspan="2" class="text-center">Valor unitario</th>
                                    <th colspan="2" class="text-center">Valor total</th>
                                    <th rowspan="2" style="vertical-align: middle;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: middle;">Rentabilidad</th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr>
                                    <th class="text-center"><!-- Valor CCL -->Compra</th>
                                    <th class="text-center"><!-- Valor CCL -->Hoy</th>
                                    <th class="text-center"><!-- Precio -->Compra</th>
                                    <th class="text-center"><!-- Precio -->Hoy</th>
                                    <th class="text-center"><!-- X -->Compra</th>
                                    <th class="text-center"><!-- X -->Hoy</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-cedear-dolares">
                                <?php
                                $cedear = obtenerCedear($cliente_id);
                                $promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;
                                foreach ($cedear as $c) {
                                    $precio_actual = obtenerPrecioActualCedear($c['ticker_cedear']);
                                    $valor_compra_ccl = obtenerCCLCompraCedear($cliente_id, $c['ticker_cedear']);
                                    $precio_actual_dolares = $precio_actual / $promedio_ccl;
                                    $valor_inicial_cedear_dolares = ($c['precio_cedear'] * $c['cantidad_cedear']) / $valor_compra_ccl;
                                    $valor_actual_cedear_dolares = ($precio_actual * $c['cantidad_cedear']) / $promedio_ccl;
                                    $rendimiento_cedear_dolares = $valor_actual_cedear_dolares - $valor_inicial_cedear_dolares;
                                    $rentabilidad_cedear_dolares = (($valor_actual_cedear_dolares - $valor_inicial_cedear_dolares) / $valor_inicial_cedear_dolares) * 100;

                                    echo "<tr data-ticker='{$c['ticker_cedear']}'>
                                    <td>{$c['ticker_cedear']}</td>
                                    <td>" . htmlspecialchars(formatearFechaCedear($c['fecha_cedear'])) . "</td>
                                    <td class='text-right'>" . htmlspecialchars(formatear_numero($c['cantidad_cedear'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($valor_compra_ccl)) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($promedio_ccl)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($c['precio_cedear'] / $valor_compra_ccl)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($precio_actual_dolares)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($valor_inicial_cedear_dolares)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($valor_actual_cedear_dolares)) . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_cedear_dolares, 'u$s') . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_cedear_dolares) . "</td>
                                    <td class='text-center'>
                                        <div class='dropdown d-flex justify-content-center'>
                                            <button class='btn custom-btn dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false' title='Opciones'>
                                                <i class='fa-solid fa-bars'></i>
                                            </button>
                                            <ul class='dropdown-menu dropdown-menu-end' aria-labelledby='dropdownMenuButton'>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/compra_mas_cedears.php?cliente_id={$cliente_id}&ticker={$c['ticker_cedear']}'><i class='fa-solid fa-plus me-2'></i>Comprar mas</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/venta_parcial_cedears.php?cliente_id={$cliente_id}&ticker={$c['ticker_cedear']}'><i class='fa-solid fa-percent me-2'></i>Venta parcial</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/venta_total_cedears.php?cliente_id={$cliente_id}&ticker={$c['ticker_cedear']}'><i class='fa-solid fa-coins me-2'></i>Venta total</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/editar_compra_cedears.php?cliente_id={$cliente_id}&ticker={$c['ticker_cedear']}'><i class='fa-solid fa-edit me-2'></i>Editar</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item eliminar text-danger' href='#' onclick='eliminarCedear(this)'><i class='fa-solid fa-trash me-2'></i>Eliminar</a>
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
                    <!-- Fin Completa Cedear Dólares -->

                </div>
                <!-- Fin Cedear Dólares -->

                <hr class="linea-accion">

                <!-- Comprar Cedear -->
                <div class="text-start">
                    <a href="../funciones/compra_cedears.php?cliente_id=<?php echo $cliente_id; ?>" class="btn btn-custom ver">
                        <i class="fa-solid fa-cart-shopping me-2"></i>Comprar
                    </a>
                </div>
                <!-- Fin Comprar Cedear -->

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

                <hr class="linea-accion">

                <!-- Bonos Pesos -->
                <div id="tablaBonosPesos">

                    <!-- Consolidada Bonos Pesos -->
                    <h6 class="me-2 cartera posiciones mb-4">Posición Consolidada</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Valor Total Inicial</th>
                                    <th>Valor Total Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_bonos_pesos)); ?></td>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_bonos_pesos)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_bonos_pesos); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_bonos_pesos); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Bonos Pesos -->

                    <hr class="linea-accion">

                    <!-- Completa Bonos Pesos -->
                    <h6 class="me-2 cartera posiciones mb-4">Posición Detallada</h6>
                    <div class="table-responsive">
                        <table id="tenencia_bonos_pesos" class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: middle;">Fecha</th>
                                    <th rowspan="2" class="text-center" style="vertical-align: middle;">Cantidad</th>
                                    <th colspan="2" class="text-center">Valor unitario</th>
                                    <th colspan="2" class="text-center">Valor total</th>
                                    <th rowspan="2" style="vertical-align: middle;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: middle;">Rentabilidad</th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr>
                                    <th class="text-center"><!-- Precio -->Compra</th>
                                    <th class="text-center"><!-- Precio -->Hoy</th>
                                    <th class="text-center"><!-- Valor -->Compra</th>
                                    <th class="text-center"><!-- Valor -->Hoy</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-bonos-pesos">
                                <?php
                                $bonos = obtenerBonos($cliente_id);
                                foreach ($bonos as $bono) {
                                    $precio_actual = obtenerValorActualRava($bono['ticker_bonos']);
                                    $valor_inicial_bonos_pesos = $bono['precio_bonos'] * $bono['cantidad_bonos'];
                                    $valor_actual_bonos_pesos = $precio_actual * $bono['cantidad_bonos'];
                                    $rendimiento_bonos_pesos = $valor_actual_bonos_pesos - $valor_inicial_bonos_pesos;
                                    $rentabilidad_bonos_pesos = (($rendimiento_bonos_pesos) / $valor_inicial_bonos_pesos) * 100;

                                    echo "<tr data-ticker='{$bono['ticker_bonos']}'>
                                    <td>{$bono['ticker_bonos']}</td>
                                    <td>" . htmlspecialchars(formatearFechaBonos($bono['fecha_bonos'])) . "</td>
                                    <td class='text-right'>" . htmlspecialchars(formatear_numero($bono['cantidad_bonos'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($bono['precio_bonos'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($precio_actual)) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($valor_inicial_bonos_pesos)) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($valor_actual_bonos_pesos)) . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_bonos_pesos) . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_bonos_pesos) . "</td>
                                    <td class='text-center'>
                                        <div class='dropdown d-flex justify-content-center'>
                                            <button class='btn custom-btn dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false' title='Opciones'>
                                                <i class='fa-solid fa-bars'></i>
                                            </button>
                                            <ul class='dropdown-menu dropdown-menu-end' aria-labelledby='dropdownMenuButton'>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/compra_mas_bonos.php?cliente_id={$cliente_id}&ticker={$bono['ticker_bonos']}'><i class='fa-solid fa-plus me-2'></i>Comprar mas</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/venta_parcial_bonos.php?cliente_id={$cliente_id}&ticker={$bono['ticker_bonos']}'><i class='fa-solid fa-percent me-2'></i>Venta parcial</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/venta_total_bonos.php?cliente_id={$cliente_id}&ticker={$bono['ticker_bonos']}'><i class='fa-solid fa-coins me-2'></i>Venta total</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/editar_compra_bonos.php?cliente_id={$cliente_id}&ticker={$bono['ticker_bonos']}'><i class='fa-solid fa-edit me-2'></i>Editar</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item eliminar text-danger' href='#' onclick='eliminarBono(this)'><i class='fa-solid fa-trash me-2'></i>Eliminar</a>
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
                    <!-- Fin Completa Bonos Pesos -->

                </div>
                <!-- Fin Bonos Pesos -->

                <!-- Bonos Dólares -->
                <div id="tablaBonosDolares" class="d-none">

                    <!-- Consolidada Bonos Dólares -->
                    <h6 class="me-2 cartera posiciones mb-4">Posición Consolidada</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Valor Total Inicial</th>
                                    <th>Valor Total Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_bonos_dolares)); ?></td>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_bonos_dolares)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_bonos_dolares, 'u$s'); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_bonos_dolares); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Bonos Dólares -->

                    <hr class="linea-accion">

                    <!-- Completa Bonos Dólares -->
                    <h6 class="me-2 cartera posiciones mb-4">Posición Detallada</h6>
                    <div class="table-responsive">
                        <table id="tenencia_bonos_dolares" class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: middle;">Fecha</th>
                                    <th rowspan="2" class="text-center" style="vertical-align: middle;">Cantidad</th>
                                    <th colspan="2" class="text-center">Dólar CCL</th>
                                    <th colspan="2" class="text-center">Valor unitario</th>
                                    <th colspan="2" class="text-center">Valor total</th>
                                    <th rowspan="2" style="vertical-align: middle;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: middle;">Rentabilidad</th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr>
                                    <th class="text-center"><!-- Valor CCL -->Compra</th>
                                    <th class="text-center"><!-- Valor CCL -->Hoy</th>
                                    <th class="text-center"><!-- Precio -->Compra</th>
                                    <th class="text-center"><!-- Precio -->Hoy</th>
                                    <th class="text-center"><!-- X -->Compra</th>
                                    <th class="text-center"><!-- X -->Hoy</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-bonos-dolares">
                                <?php
                                $bonos = obtenerBonos($cliente_id);
                                $promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;
                                foreach ($bonos as $bono) {
                                    // pesos
                                    $precio_actual = obtenerValorActualRava($bono['ticker_bonos']);
                                    $valor_inicial_bonos_pesos = $bono['precio_bonos'] * $bono['cantidad_bonos'];
                                    $valor_actual_bonos_pesos = $precio_actual * $bono['cantidad_bonos'];
                                    // dolares
                                    $valor_compra_ccl = obtenerCCLCompraBonos($cliente_id, $bono['ticker_bonos']);
                                    $precio_actual_dolares = $precio_actual / $promedio_ccl;
                                    $valor_inicial_bonos_dolares = $valor_inicial_bonos_pesos / $valor_compra_ccl;
                                    $valor_actual_bonos_dolares = $valor_actual_bonos_pesos / $promedio_ccl;
                                    $rendimiento_bonos_dolares = $valor_actual_bonos_dolares - $valor_inicial_bonos_dolares;
                                    $rentabilidad_bonos_dolares = (($valor_actual_bonos_dolares - $valor_inicial_bonos_dolares) / $valor_inicial_bonos_dolares) * 100;

                                    echo "<tr data-ticker='{$bono['ticker_bonos']}'>
                                    <td>{$bono['ticker_bonos']}</td>
                                    <td>" . htmlspecialchars(formatearFechaBonos($bono['fecha_bonos'])) . "</td>
                                    <td class='text-right'>" . htmlspecialchars(formatear_numero($bono['cantidad_bonos'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($valor_compra_ccl)) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($promedio_ccl)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($bono['precio_bonos'] / $valor_compra_ccl)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($precio_actual_dolares)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($valor_inicial_bonos_dolares)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($valor_actual_bonos_dolares)) . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_bonos_dolares, 'u$s') . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_bonos_dolares) . "</td>
                                    <td class='text-center'>
                                        <div class='dropdown d-flex justify-content-center'>
                                            <button class='btn custom-btn dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false' title='Opciones'>
                                                <i class='fa-solid fa-bars'></i>
                                            </button>
                                            <ul class='dropdown-menu dropdown-menu-end' aria-labelledby='dropdownMenuButton'>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/compra_mas_bonos.php?cliente_id={$cliente_id}&ticker={$bono['ticker_bonos']}'><i class='fa-solid fa-plus me-2'></i>Comprar mas</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/venta_parcial_bonos.php?cliente_id={$cliente_id}&ticker={$bono['ticker_bonos']}'><i class='fa-solid fa-percent me-2'></i>Venta parcial</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/venta_total_bonos.php?cliente_id={$cliente_id}&ticker={$bono['ticker_bonos']}'><i class='fa-solid fa-coins me-2'></i>Venta total</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/editar_compra_bonos.php?cliente_id={$cliente_id}&ticker={$bono['ticker_bonos']}'><i class='fa-solid fa-edit me-2'></i>Editar</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item eliminar text-danger' href='#' onclick='eliminarBono(this)'><i class='fa-solid fa-trash me-2'></i>Eliminar</a>
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
                    <!-- Fin Completa Bonos Dólares -->

                </div>
                <!-- Fin Bonos Dólares -->

                <hr class="linea-accion">

                <!-- Comprar Bonos -->
                <div class="text-start">
                    <a href="../funciones/compra_bonos.php?cliente_id=<?php echo $cliente_id; ?>" class="btn btn-custom ver">
                        <i class="fa-solid fa-cart-shopping me-2"></i>Comprar
                    </a>
                </div>
                <!-- Fin Comprar Bonos -->

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

                <hr class="linea-accion">

                <!-- Fondos Pesos -->
                <div id="tablaFondosPesos">

                    <!-- Consolidada Fondos Pesos -->
                    <h6 class="me-2 cartera posiciones mb-4">Posición Consolidada</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Valor Total Inicial</th>
                                    <th>Valor Total Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_fondos_pesos)); ?></td>
                                    <td>$ <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_fondos_pesos)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_fondos_pesos); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_fondos_pesos); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Fondos Pesos -->

                    <hr class="linea-accion">

                    <!-- Completa Fondos Pesos -->
                    <h6 class="me-2 cartera posiciones mb-4">Posición Detallada</h6>
                    <div class="table-responsive">
                        <table id="tenencia_fondos_pesos" class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: middle;">Fecha</th>
                                    <th rowspan="2" class="text-center" style="vertical-align: middle;">Cantidad</th>
                                    <th colspan="2" class="text-center">Valor unitario</th>
                                    <th colspan="2" class="text-center">Valor total</th>
                                    <th rowspan="2" style="vertical-align: middle;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: middle;">Rentabilidad</th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr>
                                    <th class="text-center"><!-- Precio -->Compra</th>
                                    <th class="text-center"><!-- Precio -->Hoy</th>
                                    <th class="text-center"><!-- Valor -->Compra</th>
                                    <th class="text-center"><!-- Valor -->Hoy</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-fondos-pesos">
                                <?php
                                $fondos = obtenerFondos($cliente_id);
                                foreach ($fondos as $fondo) {
                                    $precio_actual = obtenerValorActualRavaFondos($fondo['ticker_fondos']);
                                    $valor_inicial_fondos_pesos = $fondo['precio_fondos'] * $fondo['cantidad_fondos'];
                                    $valor_actual_fondos_pesos = $precio_actual * $fondo['cantidad_fondos'];
                                    $rendimiento_fondos_pesos = $valor_actual_fondos_pesos - $valor_inicial_fondos_pesos;
                                    $rentabilidad_fondos_pesos = (($rendimiento_fondos_pesos) / $valor_inicial_fondos_pesos) * 100;

                                    echo "<tr data-ticker='{$fondo['ticker_fondos']}'>
                                    <td>{$fondo['ticker_fondos']}</td>
                                    <td>" . htmlspecialchars(formatearFechaFondos($fondo['fecha_fondos'])) . "</td>
                                    <td class='text-right'>" . htmlspecialchars(formatear_numero($fondo['cantidad_fondos'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($fondo['precio_fondos'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($precio_actual)) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($valor_inicial_fondos_pesos)) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($valor_actual_fondos_pesos)) . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_fondos_pesos) . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_fondos_pesos) . "</td>
                                    <td class='text-center'>
                                        <div class='dropdown d-flex justify-content-center'>
                                            <button class='btn custom-btn dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false' title='Opciones'>
                                                <i class='fa-solid fa-bars'></i>
                                            </button>
                                            <ul class='dropdown-menu dropdown-menu-end' aria-labelledby='dropdownMenuButton'>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/compra_mas_fondos.php?cliente_id={$cliente_id}&ticker={$fondo['ticker_fondos']}'><i class='fa-solid fa-plus me-2'></i>Comprar mas</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/venta_parcial_fondos.php?cliente_id={$cliente_id}&ticker={$fondo['ticker_fondos']}'><i class='fa-solid fa-percent me-2'></i>Venta parcial</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/venta_total_fondos.php?cliente_id={$cliente_id}&ticker={$fondo['ticker_fondos']}'><i class='fa-solid fa-coins me-2'></i>Venta total</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/editar_compra_fondos.php?cliente_id={$cliente_id}&ticker={$fondo['ticker_fondos']}'><i class='fa-solid fa-edit me-2'></i>Editar</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item eliminar text-danger' href='#' onclick='eliminarFondo(this)'><i class='fa-solid fa-trash me-2'></i>Eliminar</a>
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
                    <!-- Fin Completa Fondos Pesos -->

                </div>
                <!-- Fin Fondos Pesos -->

                <!-- Fondos Dólares -->
                <div id="tablaFondosDolares" class="d-none">

                    <!-- Consolidada Fondos Dólares -->
                    <h6 class="me-2 cartera posiciones mb-4">Posición Consolidada</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Valor Total Inicial</th>
                                    <th>Valor Total Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_inicial_consolidado_fondos_dolares)); ?></td>
                                    <td>u$s <?php echo htmlspecialchars(formatear_dinero($valor_actual_consolidado_fondos_dolares)); ?></td>
                                    <td><?php echo formatear_y_colorear_valor($rendimiento_consolidado_fondos_dolares, 'u$s'); ?></td>
                                    <td><?php echo formatear_y_colorear_porcentaje($rentabilidad_consolidado_fondos_dolares); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Fin Consolidada Fondos Dólares -->

                    <hr class="linea-accion">

                    <!-- Completa Fondos Dólares -->
                    <h6 class="me-2 cartera posiciones mb-4">Posición Detallada</h6>
                    <div class="table-responsive">
                        <table id="tenencia_fondos_dolares" class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">Ticker</th>
                                    <th rowspan="2" style="vertical-align: middle;">Fecha</th>
                                    <th rowspan="2" class="text-center" style="vertical-align: middle;">Cantidad</th>
                                    <th colspan="2" class="text-center">Dólar CCL</th>
                                    <th colspan="2" class="text-center">Valor unitario</th>
                                    <th colspan="2" class="text-center">Valor total</th>
                                    <th rowspan="2" style="vertical-align: middle;">Rendimiento</th>
                                    <th rowspan="2" style="vertical-align: middle;">Rentabilidad</th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr>
                                    <th class="text-center"><!-- Valor CCL -->Compra</th>
                                    <th class="text-center"><!-- Valor CCL -->Hoy</th>
                                    <th class="text-center"><!-- Precio -->Compra</th>
                                    <th class="text-center"><!-- Precio -->Hoy</th>
                                    <th class="text-center"><!-- X -->Compra</th>
                                    <th class="text-center"><!-- X -->Hoy</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-fondos-dolares">
                                <?php
                                $fondos = obtenerFondos($cliente_id);
                                $promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;
                                foreach ($fondos as $fondo) {
                                    // pesos
                                    $precio_actual = obtenerValorActualRavaFondos($fondo['ticker_fondos']);
                                    $valor_inicial_fondos_pesos = $fondo['precio_fondos'] * $fondo['cantidad_fondos'];
                                    $valor_actual_fondos_pesos = $precio_actual * $fondo['cantidad_fondos'];
                                    // dolares
                                    $valor_compra_ccl = obtenerCCLCompraFondos($cliente_id, $fondo['ticker_fondos']);
                                    $precio_actual_dolares = $precio_actual / $promedio_ccl;
                                    $valor_inicial_fondos_dolares = $valor_inicial_fondos_pesos / $valor_compra_ccl;
                                    $valor_actual_fondos_dolares = $valor_actual_fondos_pesos / $promedio_ccl;
                                    $rendimiento_fondos_dolares = $valor_actual_fondos_dolares - $valor_inicial_fondos_dolares;
                                    $rentabilidad_fondos_dolares = (($valor_actual_fondos_dolares - $valor_inicial_fondos_dolares) / $valor_inicial_fondos_dolares) * 100;

                                    echo "<tr data-ticker='{$fondo['ticker_fondos']}'>
                                    <td>{$fondo['ticker_fondos']}</td>
                                    <td>" . htmlspecialchars(formatearFechaFondos($fondo['fecha_fondos'])) . "</td>
                                    <td class='text-right'>" . htmlspecialchars(formatear_numero($fondo['cantidad_fondos'])) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($valor_compra_ccl)) . "</td>
                                    <td class='text-right'>$ " . htmlspecialchars(formatear_dinero($promedio_ccl)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($fondo['precio_fondos'] / $valor_compra_ccl)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($precio_actual_dolares)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($valor_inicial_fondos_dolares)) . "</td>
                                    <td class='text-right'>u\$s " . htmlspecialchars(formatear_dinero($valor_actual_fondos_dolares)) . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_valor($rendimiento_fondos_dolares, 'u$s') . "</td>
                                    <td class='text-right'>" . formatear_y_colorear_porcentaje($rentabilidad_fondos_dolares) . "</td>
                                    <td class='text-center'>
                                        <div class='dropdown d-flex justify-content-center'>
                                            <button class='btn custom-btn dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false' title='Opciones'>
                                                <i class='fa-solid fa-bars'></i>
                                            </button>
                                            <ul class='dropdown-menu dropdown-menu-end' aria-labelledby='dropdownMenuButton'>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/compra_mas_fondos.php?cliente_id={$cliente_id}&ticker={$fondo['ticker_fondos']}'><i class='fa-solid fa-plus me-2'></i>Comprar mas</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/venta_parcial_fondos.php?cliente_id={$cliente_id}&ticker={$fondo['ticker_fondos']}'><i class='fa-solid fa-percent me-2'></i>Venta parcial</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/venta_total_fondos.php?cliente_id={$cliente_id}&ticker={$fondo['ticker_fondos']}'><i class='fa-solid fa-coins me-2'></i>Venta total</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='../funciones/editar_compra_fondos.php?cliente_id={$cliente_id}&ticker={$fondo['ticker_fondos']}'><i class='fa-solid fa-edit me-2'></i>Editar</a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item eliminar text-danger' href='#' onclick='eliminarFondo(this)'><i class='fa-solid fa-trash me-2'></i>Eliminar</a>
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
                    <!-- Fin Completa Fondos Dólares -->

                </div>
                <!-- Fin Fondos Dólares -->

                <hr class="linea-accion">

                <!-- Comprar Fondos -->
                <div class="text-start">
                    <a href="../funciones/compra_fondos.php?cliente_id=<?php echo $cliente_id; ?>" class="btn btn-custom ver">
                        <i class="fa-solid fa-cart-shopping me-2"></i>Comprar
                    </a>
                </div>
                <!-- Fin Comprar Fondos -->

            </div>
        </div>
        <!-- FIN FONDOS -->

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

    <!-- MODALES ELIMINACIÓN -->
    <!-- Acciones -->
    <div class="modal fade" id="confirmDeleteModalAcciones" tabindex="-1" aria-labelledby="confirmDeleteModalAccionesLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white" id="confirmDeleteModalAccionesLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">¿Estás seguro que querés eliminar esta acción?</p>
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Se eliminarán todos los datos relacionados con esta acción. <br><b>Esta acción no se puede deshacer.</b>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-custom cancelar" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtnAcciones">
                        <i class="fa-solid fa-check me-2"></i>Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin Acciones -->
    <!-- Cedears -->
    <div class="modal fade" id="confirmDeleteModalCedears" tabindex="-1" aria-labelledby="confirmDeleteModalCedearsLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white" id="confirmDeleteModalCedearsLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">¿Estás seguro que querés eliminar este Cedear?</p>
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Se eliminarán todos los datos relacionados con este Cedear. <br><b>Esta acción no se puede deshacer.</b>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-custom cancelar" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtnCedears">
                        <i class="fa-solid fa-check me-2"></i>Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin Cedears -->
    <!-- Bonos -->
    <div class="modal fade" id="confirmDeleteModalBonos" tabindex="-1" aria-labelledby="confirmDeleteModalBonosLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white" id="confirmDeleteModalBonosLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">¿Estás seguro que querés eliminar este bono?</p>
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Se eliminarán todos los datos relacionados con este bono. <br><b>Esta acción no se puede deshacer.</b>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-custom cancelar" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtnBonos">
                        <i class="fa-solid fa-check me-2"></i>Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin Bonos -->
    <!-- Fondos -->
    <div class="modal fade" id="confirmDeleteModalFondos" tabindex="-1" aria-labelledby="confirmDeleteModalFondosLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white" id="confirmDeleteModalFondosLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">¿Estás seguro que querés eliminar este fondo?</p>
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Se eliminarán todos los datos relacionados con este fondo. <br><b>Esta acción no se puede deshacer.</b>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-custom cancelar" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtnFondos">
                        <i class="fa-solid fa-check me-2"></i>Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin Fondos -->
    <!-- FIN MODALES ELIMINACIÓN -->

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="../js/preloader.js"></script>
    <script src="../js/tooltip.js"></script>
    <script src="../js/easter_egg.js"></script>
    <script type="module" src="../js/filtro_tablas.js"></script>
    <script type="module" src="../js/botones_pesos_dolares.js"></script>
    <script src="../js/eliminar_acciones.js"></script>
    <script src="../js/eliminar_cedears.js"></script>
    <script src="../js/eliminar_bonos.js"></script>
    <script src="../js/eliminar_fondos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../js/graficos.js"></script>
    <!-- FIN JS -->

</body>

</html>