<?php
// Incluir archivo de configuración
require_once '../../config/config.php';

// Incluir las funciones necesarias
include '../funciones/dolar_api.php';
include '../funciones/balance.php';

// Obtener el id del cliente desde la URL
$cliente_id = isset($_GET['cliente_id']) ? $_GET['cliente_id'] : 1;

// Consulta para obtener los datos del cliente
$sql = "SELECT nombre, apellido FROM clientes WHERE cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$stmt->bind_result($nombre, $apellido);
$stmt->fetch();
$stmt->close();

// Obtener el saldo en efectivo del balance
$saldo_efectivo = $balance['efectivo'];
$saldo_dolares = calcular_saldo_dolares($saldo_efectivo, $contadoconliqui_compra, $contadoconliqui_venta);

// Nueva función para obtener las acciones del cliente
function obtener_acciones_cliente($conn, $cliente_id)
{
    $sql = "SELECT ticker, fecha, cantidad, precio FROM acciones WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $ticker = $fecha = $cantidad = $precio = null;
    $stmt->bind_result($ticker, $fecha, $cantidad, $precio);

    $acciones = [];
    while ($stmt->fetch()) {
        $acciones[] = [
            'ticker' => $ticker,
            'fecha' => $fecha,
            'cantidad' => $cantidad,
            'precio' => $precio,
        ];
    }
    $stmt->close();
    return $acciones;
}

// Obtener las acciones del cliente
$acciones = obtener_acciones_cliente($conn, $cliente_id);

$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;

// Nueva función para calcular el valor inicial de las acciones en pesos
function calcular_valor_inicial_acciones($acciones)
{
    $valor_inicial_total = 0;
    foreach ($acciones as $accion) {
        $valor_inicial_total += $accion['cantidad'] * $accion['precio'];
    }
    return $valor_inicial_total;
}

// Calcular el valor inicial de las acciones en pesos
$valor_inicial_acciones_pesos = calcular_valor_inicial_acciones($acciones);

function obtener_valor_accion($ticker)
{
    $url = "https://www.google.com/finance/quote/{$ticker}:BCBA?hl=es";

    // Realizar la solicitud HTTP a la URL
    $html = file_get_contents($url);

    if ($html === false) {
        // Si no pudimos obtener el contenido de la página, devolver null
        return null;
    }

    // Buscar el valor dentro de la página HTML
    $pattern = '/<div class="YMlKec fxKbKc">([^<]+)<\/div>/';
    preg_match($pattern, $html, $matches);

    // Verificamos si encontramos el valor
    if (isset($matches[1])) {
        // Limpiar el valor numérico, quitando cualquier texto no necesario
        $valor = $matches[1];

        // Eliminar cualquier símbolo de moneda, como "$"
        $valor = preg_replace('/[^0-9,.-]/', '', $valor);

        // Cambiar la coma por un punto para el separador de miles
        // y reemplazar el punto por coma para decimales
        $valor = str_replace('.', '', $valor); // Eliminar puntos de miles
        $valor = str_replace(',', '.', $valor); // Convertir la coma a punto decimal

        // Convertir el valor a formato numérico
        $valor = (float)$valor;

        // Devolver el valor numérico limpio
        return $valor;
    }

    // Si no encontramos el valor, devolver null
    return null;
}
// Inicializamos una variable para acumular el total de las acciones en pesos
$total_valor_acciones_pesos = 0;

// Iteramos sobre las acciones del cliente
foreach ($acciones as $accion) {
    // Formateamos la cantidad de acciones (si es nula, lo ponemos como 0)
    $cantidad_compra_acciones_formateada = is_null($accion['cantidad']) ? 0 : $accion['cantidad'];

    // Obtenemos el valor actual de la acción (esto debe ser un valor numérico)
    $valor_actual_accion = obtener_valor_accion($accion['ticker']); // Obtenemos el valor sin multiplicar por promedio_ccl aún

    // Si el valor obtenido es 0 o nulo, lo manejamos adecuadamente
    if ($valor_actual_accion === null || $valor_actual_accion == 0) {
        $valor_actual_accion = 0; // Asignamos 0 si no se obtiene un valor válido
    }

    // Multiplicamos el valor actual de la acción por la cantidad de acciones
    $valor_total_acciones_pesos = $cantidad_compra_acciones_formateada * $valor_actual_accion;

    // Sumamos el valor total de esta acción al total acumulado
    $total_valor_acciones_pesos += $valor_total_acciones_pesos;
}

// Ahora podemos renderizar el valor total en la celda correspondiente
$valor_total_acciones_pesos_formateado = number_format($total_valor_acciones_pesos, 2, ',', '.');

// Calculamos el rendimiento restando el valor actual menos el valor inicial
$rendimiento_acciones_pesos = $total_valor_acciones_pesos - $valor_inicial_acciones_pesos;

// Definimos el color según el rendimiento
$color_rendimiento = ($rendimiento_acciones_pesos >= 0) ? 'green' : 'red';

// Formateamos el rendimiento para mostrarlo en la tabla
$rendimiento_acciones_pesos_formateado = number_format($rendimiento_acciones_pesos, 2, ',', '.');

// Cálculo de rentabilidad con la nueva fórmula
$rentabilidad_acciones_pesos_nueva = (($total_valor_acciones_pesos - $valor_inicial_acciones_pesos) / $total_valor_acciones_pesos) * 100;

// Formateamos la rentabilidad
$rentabilidad_acciones_pesos_nueva_formateada = number_format($rentabilidad_acciones_pesos_nueva, 2, ',', '.');

// Definimos el color de la rentabilidad: verde si es positiva o 0, rojo si es negativa
$color_rentabilidad_nueva = ($rentabilidad_acciones_pesos_nueva >= 0) ? 'green' : 'red';

// Inicializamos el total de las acciones
$total_valor_acciones_dolares = 0;

// Recorremos todas las acciones para calcular el total
foreach ($acciones as $accion) {
    // Obtener la cantidad y el valor de compra de las acciones
    $cantidad_compra_acciones = is_null($accion['cantidad']) ? 0 : $accion['cantidad'];

    // Obtenemos el valor actual de la acción desde Google Finance (o alguna fuente similar)
    $valor_actual = obtener_valor_accion($accion['ticker']);

    if ($valor_actual === null) {
        // Si no se pudo obtener el valor, usamos un valor predeterminado (0 o N/A)
        $valor_actual = 0;
    }

    // Calculamos el valor actual de las acciones en dólares usando el valor actual
    $valor_actual_dolares = $valor_actual / $promedio_ccl;

    // Acumulamos el valor total de las acciones
    $total_valor_acciones_dolares += $cantidad_compra_acciones * $valor_actual_dolares;
}

// Formateamos el total en dólares con el separador correcto
$total_valor_acciones_dolares_formateado = number_format($total_valor_acciones_dolares, 2, ',', '.');



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
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>$ <?php echo number_format($valor_inicial_acciones_pesos, 2, ',', '.'); ?></td>
                                    <td>$ <?php echo $valor_total_acciones_pesos_formateado; ?></td> <!-- Aquí renderizamos el valor total calculado -->
                                    <td style="color: <?php echo $color_rendimiento; ?>;">$ <?php echo $rendimiento_acciones_pesos_formateado; ?></td> <!-- Aquí mostramos el rendimiento con el color adecuado -->
                                    <td style="color: <?php echo $color_rentabilidad_nueva; ?>;"><?php echo $rentabilidad_acciones_pesos_nueva_formateada . ' %'; ?></td> <!-- Rentabilidad con la nueva fórmula -->
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
                                    $cantidad_compra_acciones_formateada = is_null($accion['cantidad']) ? '0' : number_format($accion['cantidad'], 0, '', '.');
                                    $valor_compra_acciones_pesos_formateado = is_null($accion['precio']) ? '0,00' : number_format($accion['precio'], 2, ',', '.');
                                    $fecha_compra_acciones_formateada = is_null($accion['fecha']) ? 'N/A' : date("d-m-Y", strtotime($accion['fecha']));

                                    // Obtener el valor actual de la acción en pesos
                                    $valor_actual = obtener_valor_accion($accion['ticker']);
                                    if ($valor_actual !== null) {
                                        // Formatear el valor para mostrarlo con punto como separador de miles y coma como separador decimal
                                        $valor_actual_pesos_formateado = number_format($valor_actual, 2, ',', '.');

                                        // Realizar el cálculo para el rendimiento
                                        $valor_actual_pesos = $valor_actual; // valor actual de la acción en pesos
                                        $valor_compra_pesos = $accion['precio']; // precio de compra de la acción

                                        // Calcular el rendimiento: (cantidad * valor_actual) - (cantidad * valor_compra)
                                        $rendimiento = ($accion['cantidad'] * $valor_actual_pesos) - ($accion['cantidad'] * $valor_compra_pesos);

                                        // Formatear el resultado con punto como separador de miles y coma como separador decimal
                                        $rendimiento_formateado = number_format($rendimiento, 2, ',', '.');

                                        // Determinar el color: verde si el rendimiento es mayor o igual a 0, rojo si es negativo
                                        $color_rendimiento = ($rendimiento >= 0) ? 'green' : 'red';

                                        // Calcular la rentabilidad: ((valor_actual - valor_compra) / valor_compra) * 100
                                        $rentabilidad = (($valor_actual_pesos - $valor_compra_pesos) / $valor_compra_pesos) * 100;

                                        // Formatear la rentabilidad con punto como separador de miles y coma como separador decimal
                                        $rentabilidad_formateada = number_format($rentabilidad, 2, ',', '.');

                                        // Determinar el color para la rentabilidad: verde si es positiva, rojo si negativa
                                        $color_rentabilidad = ($rentabilidad >= 0) ? 'green' : 'red';

                                        // Añadir el símbolo "%" al final de la rentabilidad
                                        $rentabilidad_formateada .= ' %';
                                    } else {
                                        $valor_actual_pesos_formateado = 'N/A'; // En caso de no encontrar el valor
                                        $rendimiento_formateado = 'N/A';
                                        $color_rendimiento = 'black'; // Si no se puede calcular, poner en color negro
                                        $rentabilidad_formateada = 'N/A'; // Si no se puede calcular la rentabilidad
                                        $color_rentabilidad = 'black'; // Si no se puede calcular la rentabilidad
                                    }

                                    echo "<tr data-ticker='{$accion['ticker']}'>
                                            <td>{$accion['ticker']}</td>
                                            <td>{$fecha_compra_acciones_formateada}</td>
                                            <td>{$cantidad_compra_acciones_formateada}</td>
                                            <td>$ {$valor_compra_acciones_pesos_formateado}</td>
                                            <td>$ {$valor_actual_pesos_formateado}</td>
                                            <td><span style='color: {$color_rendimiento};'>$  {$rendimiento_formateado}</span></td> <!-- rendimiento_acciones_pesos -->
                                            <td><span style='color: {$color_rentabilidad};'>{$rentabilidad_formateada}</span></td> <!-- rentabilidad_acciones_pesos -->
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
                                    <td>u$s <?php echo $total_valor_acciones_dolares_formateado; ?></td>
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
                                    $cantidad_compra_acciones_formateada = is_null($accion['cantidad']) ? '0' : number_format($accion['cantidad'], 0, '', '.');

                                    // Obtenemos el valor actual de la acción desde Google Finance
                                    $valor_actual = obtener_valor_accion($accion['ticker']); // Llamamos a la función para obtener el valor actualizado

                                    if ($valor_actual === null) {
                                        // Si no se pudo obtener el valor, mostrar un mensaje por defecto o manejar el error
                                        $valor_actual = 0; // O alguna otra acción como mostrar 'N/A' o 'Error'
                                    }

                                    // Cálculos sin formatear los números
                                    $valor_actual_dolares = $valor_actual / $promedio_ccl; // Aplicamos la fórmula sin redondeos
                                    $valor_compra_dolares = $accion['precio'] / $promedio_ccl; // Cálculo de valor compra en dólares

                                    // Formateamos los valores solo al momento de mostrar
                                    $valor_compra_dolares_formateado = number_format($valor_compra_dolares, 2, ',', '.');
                                    $valor_actual_dolares_formateado = number_format($valor_actual_dolares, 2, ',', '.'); // Lo formateamos como corresponde
                                    $fecha_compra_acciones_formateada = is_null($accion['fecha']) ? 'N/A' : date("d-m-Y", strtotime($accion['fecha']));

                                    // Calcular rentabilidad en porcentaje
                                    $rentabilidad = (($valor_actual_dolares - $valor_compra_dolares) / $valor_compra_dolares) * 100;

                                    // Formateamos el valor de la rentabilidad con 2 decimales y el símbolo de porcentaje
                                    $rentabilidad_formateada = number_format($rentabilidad, 2, ',', '.') . ' %';

                                    // Determinar el color: verde si la rentabilidad es mayor o igual a 0, rojo si es negativa
                                    $color_rentabilidad = ($rentabilidad >= 0) ? 'green' : 'red';

                                    // Cálculo del rendimiento
                                    $rendimiento = ($valor_actual_dolares * $accion['cantidad']) - ($valor_compra_dolares * $accion['cantidad']);

                                    // Formatear el resultado del rendimiento
                                    $rendimiento_formateado = number_format($rendimiento, 2, ',', '.');

                                    // Determinar el color del rendimiento
                                    $color_rendimiento = ($rendimiento >= 0) ? 'green' : 'red';

                                    echo "<tr data-ticker='{$accion['ticker']}'>
                                        <td>{$accion['ticker']}</td>
                                        <td>{$fecha_compra_acciones_formateada}</td>
                                        <td>{$cantidad_compra_acciones_formateada}</td>
                                        <td>u\$s {$valor_compra_dolares_formateado}</td>
                                        <td>u\$s {$valor_actual_dolares_formateado}</td>
                                        <td><span style='color: {$color_rendimiento};'>u\$s {$rendimiento_formateado}</span></td> <!-- rendimiento_acciones_dolares -->
                                        <td><span style='color: {$color_rentabilidad};'>{$rentabilidad_formateada}</span></td> <!-- rentabilidad_acciones_dolares -->
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
    <!-- FIN JS -->
</body>

</html>