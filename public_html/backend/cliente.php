<?php
// Incluir archivo de configuración
require_once '../../config/config.php';

// Incluir las funciones necesarias
include '../funciones/dolar_api.php';
include '../funciones/balance.php';
include '../funciones/cliente_varios.php';

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
                        <a class="nav-link" href="../index.php"><i class="fas fa-home me-2"></i>Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="lista_clientes.php"><i class="fa-solid fa-users me-2"></i>Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="alta_clientes.php"><i class="fa-solid fa-user-plus me-2"></i>Alta Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="historial.php"><i class="fa-solid fa-clock-rotate-left me-2"></i>Historial</a>
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

            <!-- ACCIONES -->
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
                            </thead>
                            <tbody>
                                <tr>
                                    <td>$ <?php echo number_format($valor_inicial_acciones_pesos, 2, ',', '.'); ?></td>
                                    <td><!-- valor_actual_acciones_pesos --></td>
                                    <td><!-- rendimiento_acciones_pesos --></td>
                                    <td><!-- rentabilidad_acciones_pesos --></td>
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
                                    <th>Ticker</th>
                                    <th>Fecha</th>
                                    <th>Cantidad</th>
                                    <th>Valor<br>Compra</th>
                                    <th>Valor<br>Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                    <th colspan="2">Venta</th>
                                    <th colspan="2">Operaciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-acciones-pesos">
                                <?php
                                foreach ($acciones as $accion) {
                                    $cantidad_formateada = is_null($accion['cantidad']) ? '0' : number_format($accion['cantidad'], 0, '', '.');
                                    $precio_formateado = is_null($accion['precio']) ? '0,00' : number_format($accion['precio'], 2, ',', '.');
                                    $fecha_formateada = is_null($accion['fecha']) ? 'N/A' : date("d-m-Y", strtotime($accion['fecha']));
                                    echo "<tr data-ticker='{$accion['ticker']}'>
                                            <td>{$accion['ticker']}</td>
                                            <td>{$fecha_formateada}</td>
                                            <td>{$cantidad_formateada}</td>
                                            <td>$ {$precio_formateado}</td>
                                            <td class='valor-actual'></td>
                                            <td><!-- rendimiento_acciones_pesos --></td>
                                            <td><!-- rentabilidad_acciones_pesos --></td>
                                            <td class='text-center'><a href='' class='btn btn-custom eliminar' data-bs-toggle='tooltip' data-bs-placement='top' title='Venta parcial'><i class='fa-solid fa-minus'></i></a></td>
                                            <td class='text-center'><a href='' class='btn btn-custom eliminar' data-bs-toggle='tooltip' data-bs-placement='top' title='Venta total'><i class='fa-solid fa-minus'></i></a></td>
                                            <td class='text-center'><a href='../funciones/editar_compra_acciones.php?cliente_id={$cliente_id}&ticker={$accion['ticker']}' class='btn btn-custom editar' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar'><i class='fa-solid fa-pen'></i></a></td>
                                            <td class='text-center'><button class='btn btn-custom eliminar' data-bs-toggle='tooltip' data-bs-placement='top' title='Eliminar' onclick=''><i class='fa-solid fa-trash'></i></button></td>
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
                                    <td>u$s <?php echo number_format($valor_inicial_acciones_pesos / $promedio_ccl, 2, ',', '.'); ?></td>
                                    <td><!-- valor_actual_acciones_dolares --></td>
                                    <td><!-- rendimiento_acciones_dolares --></td>
                                    <td><!-- rentabilidad_acciones_dolares --></td>
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
                                    <th>Ticker</th>
                                    <th>Fecha</th>
                                    <th>Cantidad</th>
                                    <th>Valor<br>Compra</th>
                                    <th>Valor<br>Actual</th>
                                    <th>Rendimiento</th>
                                    <th>Rentabilidad</th>
                                    <th colspan="2">Venta</th>
                                    <th colspan="2">Operaciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-acciones-dolares">
                                <?php
                                foreach ($acciones as $accion) {
                                    $valor_compra_dolares = $accion['precio'] / $promedio_ccl;
                                    $valor_compra_dolares_formateado = number_format($valor_compra_dolares, 2, ',', '.');
                                    $fecha_formateada = is_null($accion['fecha']) ? 'N/A' : date("d-m-Y", strtotime($accion['fecha']));

                                    echo "<tr data-ticker='{$accion['ticker']}'>
                                            <td>{$accion['ticker']}</td>
                                            <td>{$fecha_formateada}</td>
                                            <td>{$accion['cantidad']}</td>
                                            <td>u\$s {$valor_compra_dolares_formateado}</td>
                                            <td><!-- valor_acciones_dolares --></td>
                                            <td><!-- rendimiento_acciones_dolares --></td>
                                            <td><!-- rentabilidad_acciones_dolares --></td>
                                            <td class='text-center'><a href='' class='btn btn-custom eliminar' data-bs-toggle='tooltip' data-bs-placement='top' title='Venta parcial'><i class='fa-solid fa-minus'></i></a></td>
                                            <td class='text-center'><a href='' class='btn btn-custom eliminar' data-bs-toggle='tooltip' data-bs-placement='top' title='Venta total'><i class='fa-solid fa-minus'></i></a></td>
                                            <td class='text-center'><a href='../funciones/editar_compra_acciones.php?cliente_id={$cliente_id}&ticker={$accion['ticker']}' class='btn btn-custom editar' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar'><i class='fa-solid fa-pen'></i></a></td>
                                            <td class='text-center'><button class='btn btn-custom eliminar' data-bs-toggle='tooltip' data-bs-placement='top' title='Eliminar' onclick=''><i class='fa-solid fa-trash'></i></button></td>
                                        </tr>";
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                    <!-- Fin Completa Acciones Dólares -->
                </div>
                <hr class="linea-accion">

                <!-- Comprar Acciones -->
                <div class="text-start">
                    <a href="../funciones/compra_acciones.php?cliente_id=<?php echo $cliente_id; ?>" class="btn btn-custom ver">
                        <i class="fa-solid fa-cart-shopping me-2"></i>Comprar
                    </a>
                </div>
                <!-- Fin Comprar Acciones -->

            </div>
            <!-- FIN ACCIONES -->

        </div>
        <!-- FIN ACCIONES -->

        <hr class="mod">

        <!-- DOLAR API -->
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
                                <td><?php echo formatear_dinero($oficial_compra); ?></td>
                                <td><?php echo formatear_dinero($oficial_venta); ?></td>
                                <td><?php echo formatear_dinero($blue_compra); ?></td>
                                <td><?php echo formatear_dinero($blue_venta); ?></td>
                                <td><?php echo formatear_dinero($bolsa_compra); ?></td>
                                <td><?php echo formatear_dinero($bolsa_venta); ?></td>
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
                                <td><?php echo formatear_dinero($contadoconliqui_compra); ?></td>
                                <td><?php echo formatear_dinero($contadoconliqui_venta); ?></td>
                                <td><?php echo formatear_dinero($tarjeta_compra); ?></td>
                                <td><?php echo formatear_dinero($tarjeta_venta); ?></td>
                                <td><?php echo formatear_dinero($mayorista_compra); ?></td>
                                <td><?php echo formatear_dinero($mayorista_venta); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- FIN DOLAR API -->

        <hr class="mod">

        <!-- EFECTIVO -->
        <div class="col-12 text-center">
            <div class="container-fluid my-4 efectivo" id="efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Efectivo</h5>
                <div class="row">
                    <div class="col-12 col-md-4 text-start">
                        <p>Saldo en pesos: $ <span id="saldo_efectivo"><?php echo number_format($balance['efectivo'], 2, ',', '.'); ?></span></p>
                    </div>
                    <div class="col-12 col-md-4 text-start">
                        <p>Saldo en dólares: u$s <span id="saldo_dolares"><?php echo is_numeric($saldo_dolares) ? number_format($saldo_dolares, 2, ',', '.') : $saldo_dolares; ?></span></p>
                    </div>
                </div>
                <hr class="linea-accion">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center">
                            <h6 class="me-2">Ingresar efectivo</h6>
                            <input type="text" id="ingresar_efectivo" placeholder="0,00" class="form-control me-2" onkeyup="formatInput(this)" style="width: 150px; text-align: right;">
                            <input type="button" value="+" class="btn btn-info btn-custom ver" id="ingresar_btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Ingresar efectivo" style="width: 40px;">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center">
                            <h6 class="me-2">Retirar efectivo</h6>
                            <input type="text" id="retirar_efectivo" placeholder="0,00" class="form-control me-2" onkeyup="formatInput(this)" style="width: 150px; text-align: right;">
                            <input type="button" value="-" class="btn btn-info btn-custom eliminar" data-bs-toggle="tooltip" data-bs-placement="top" title="Retirar efectivo" style="width: 40px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN EFECTIVO -->

    </div>
    <!-- FIN CONTENIDO -->

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="../js/tooltip.js"></script>
    <script src="../js/botones_pesos_dolares.js"></script>
    <script src="../js/formato_miles.js"></script>
    <script src="../js/ingresa_dinero.js"></script>
    <script src="../js/retira_dinero.js"></script>
    <script src="../js/cliente_varios.js"></script>
    <!-- FIN JS -->
</body>

</html>