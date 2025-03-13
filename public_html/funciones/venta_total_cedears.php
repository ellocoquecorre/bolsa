<?php
// Incluir archivo de configuración
require_once '../../config/config.php';
require_once '../funciones/formato_dinero.php';
require_once '../funciones/cliente_funciones.php';

// Obtener el id del cliente y el ticker desde la URL
$cliente_id = isset($_GET['cliente_id']) ? $_GET['cliente_id'] : 1;
$ticker = isset($_GET['ticker']) ? $_GET['ticker'] : '';

// Obtener datos de la tabla cedear para el cliente y ticker en cuestión
$query = "SELECT * FROM cedear WHERE cliente_id = ? AND ticker_cedear = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $cliente_id, $ticker);
$stmt->execute();
$result = $stmt->get_result();
$cedear = $result->fetch_assoc();

$fecha_actual = date("d-m-Y");

// Obtener el promedio CCL
function obtenerPromedioCCL()
{
    global $contadoconliqui_compra, $contadoconliqui_venta;
    return ($contadoconliqui_compra + $contadoconliqui_venta) / 2;
}
$promedio_ccl = obtenerPromedioCCL();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Desformatear los valores antes de guardar en la base de datos
    $cantidad = str_replace('.', '', $_POST['cantidad']);
    $fecha_compra = date("Y-m-d", strtotime($_POST['fecha_compra']));
    $precio_compra = str_replace(['.', ','], ['', '.'], $_POST['precio_compra']);
    $ccl_compra = str_replace(['.', ','], ['', '.'], $_POST['ccl_compra']);
    $fecha_venta = date("Y-m-d", strtotime($_POST['fecha_venta']));
    $precio_venta = str_replace(['.', ','], ['', '.'], $_POST['precio_venta']);
    $ccl_venta = str_replace(['.', ','], ['', '.'], $_POST['ccl_venta']);
    $ticker = $_POST['ticker'];

    // Insertar en la tabla cedear_historial
    $query_historial = "
        INSERT INTO cedear_historial
        (cliente_id, ticker_cedear, cantidad_cedear, fecha_compra_cedear, precio_compra_cedear, ccl_compra, fecha_venta_cedear, precio_venta_cedear, ccl_venta)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt_historial = $conn->prepare($query_historial);
    $stmt_historial->bind_param("isissdssd", $cliente_id, $ticker, $cantidad, $fecha_compra, $precio_compra, $ccl_compra, $fecha_venta, $precio_venta, $ccl_venta);
    $stmt_historial->execute();

    // Calcular el nuevo balance
    $total_venta = $cantidad * $precio_venta;
    $query_balance = "UPDATE balance SET efectivo = efectivo + ? WHERE cliente_id = ?";
    $stmt_balance = $conn->prepare($query_balance);
    $stmt_balance->bind_param("di", $total_venta, $cliente_id);
    $stmt_balance->execute();

    // Eliminar la línea de la tabla cedear
    $query_delete = "DELETE FROM cedear WHERE cliente_id = ? AND ticker_cedear = ?";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bind_param("is", $cliente_id, $ticker);
    $stmt_delete->execute();

    // Redirigir a la misma URL que el botón Cancelar
    header("Location: ../backend/cliente.php?cliente_id={$cliente_id}#cedears");
    exit();
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="../backend/lista_clientes.php"><i class="fa-solid fa-users me-2"></i>Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../backend/alta_clientes.php"><i class="fa-solid fa-user-plus me-2"></i>Alta Clientes</a>
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
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <!-- VENTA TOTAL CEDEARS -->
        <div class="col-2"></div>
        <div class="col-8 text-center">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Venta total</h5>

                <form id="venta_total" method="POST" action="">
                    <input type="hidden" name="cliente_id" value="<?php echo $cliente_id; ?>">
                    <input type="hidden" name="ticker" value="<?php echo $cedear['ticker_cedear']; ?>">

                    <!-- Primera Fila -->
                    <div class="row">
                        <!-- Izquierda -->
                        <div class="col-6 text-center">
                            <!-- Ticker -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4" for="ticker" class="col-sm-2 col-form-label">Ticker</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fa-solid fa-chart-line"></i></span>
                                        <input type="text" class="form-control" id="ticker" name="ticker"
                                            value="<?php echo $cedear['ticker_cedear']; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Ticker -->
                        </div>
                        <!-- Fin Izquierda -->

                        <!-- Derecha -->
                        <div class="col-6 text-center">
                            <!-- Cantidad -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4" for="cantidad" class="col-sm-2 col-form-label">Cantidad</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fa-solid fa-hashtag"></i></span>
                                        <input type="text" class="form-control"
                                            id="cantidad" name="cantidad" value="<?php echo formatear_numero($cedear['cantidad_cedear']); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Cantidad -->
                        </div>
                        <!-- Fin Derecha -->
                    </div>
                    <!-- Fin Primera Fila -->

                    <hr class="linea-accion">

                    <!-- Segunda Fila -->
                    <div class="row">
                        <!-- Izquierda -->
                        <div class="col-6 text-center">
                            <!-- Fecha Compra -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4" for="fecha_compra" class="col-sm-2 col-form-label">Fecha Compra</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-alt"></i></span>
                                        <input type="text" class="form-control" id="fecha_compra" name="fecha_compra"
                                            value="<?php echo date("d-m-Y", strtotime($cedear['fecha_cedear'])); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Fecha Compra -->

                            <!-- Precio Compra -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4" for="precio_compra" class="col-sm-2 col-form-label">Precio Compra</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                        <input type="text" class="form-control" id="precio_compra" name="precio_compra"
                                            value="<?php echo formatear_dinero($cedear['precio_cedear']); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Precio Compra -->

                            <!-- CCL Compra -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4" for="ccl_compra" class="col-sm-2 col-form-label">CCL Compra</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                        <input type="text" class="form-control" id="ccl_compra" name="ccl_compra"
                                            value="<?php echo formatear_dinero($cedear['ccl_compra_cedear']); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin CCL Compra -->
                        </div>
                        <!-- Fin Izquierda -->

                        <!-- Derecha -->
                        <div class="col-6 text-center">
                            <!-- Fecha Venta -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4" for="fecha_venta" class="col-sm-2 col-form-label">Fecha Venta</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-alt"></i></span>
                                        <input type="text" class="form-control" id="fecha_venta" name="fecha_venta"
                                            value="<?php echo $fecha_actual; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Fecha Venta -->

                            <!-- Precio Venta -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4" for="precio_venta" class="col-sm-2 col-form-label">Precio Venta</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                        <input type="text" class="form-control" id="precio_venta" name="precio_venta"
                                            placeholder="0,00" required autofocus>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Precio Venta -->

                            <!-- CCL Venta -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4" for="ccl_venta" class="col-sm-2 col-form-label">CCL Venta</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                        <input type="text" class="form-control" id="ccl_venta" name="ccl_venta"
                                            value="<?php echo formatear_dinero($promedio_ccl); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin CCL Venta -->
                        </div>
                        <!-- Fin Derecha -->
                    </div>
                    <!-- Fin Segunda Fila -->

                    <hr class="mod mb-3">

                    <!-- Botones -->
                    <div class="text-end">
                        <button type="submit" id="btnAceptar" class="btn btn-custom ver"><i class="fa-solid fa-check me-2"></i>Aceptar</button>
                        <a href="../backend/cliente.php?cliente_id=<?php echo $cliente_id; ?>#cedears"
                            class="btn btn-custom eliminar"><i class="fa-solid fa-times me-2"></i>Cancelar</a>
                    </div>
                    <!-- Fin Botones -->
                </form>
            </div>
        </div>
        <div class="col-2"></div>
        <!-- FIN VENTA TOTAL CEDEARS -->
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
    <!-- FIN JS -->
</body>

</html>