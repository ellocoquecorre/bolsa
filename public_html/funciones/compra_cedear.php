<?php
// Incluir archivo de configuración
require_once '../../config/config.php';

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

// Consulta para obtener el valor de "efectivo" de la tabla "balance"
$sql = "SELECT efectivo FROM balance WHERE cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$stmt->bind_result($efectivo);
$stmt->fetch();
$stmt->close();

// Formatear el valor de "efectivo" con el signo "$", punto (.) como separador de miles y coma (,) como separador de decimales
$saldo_formateado = '$ ' . number_format($efectivo, 2, ',', '.');

// Obtener la fecha de hoy
$fecha_cedear_hoy = date('Y-m-d');

// Inicializar variable de mensaje de error
$error_msg = "";

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $ticker_cedear = $_POST['ticker'];
    $cantidad_cedear = $_POST['cantidad'];
    $precio_cedear = $_POST['precio'];
    $fecha_cedear = $_POST['fecha'];

    // Calcular el total de la operación
    $total_operacion_cedear = $cantidad_cedear * $precio_cedear;

    // Obtener el valor de "efectivo" de la tabla "balance"
    $sql = "SELECT efectivo FROM balance WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $stmt->bind_result($efectivo);
    $stmt->fetch();
    $stmt->close();

    // Verificar si hay suficiente saldo
    if ($total_operacion_cedear <= $efectivo) {
        // Restar el total de la operación al efectivo
        $nuevo_efectivo = $efectivo - $total_operacion_cedear;

        // Actualizar el valor de "efectivo" en la tabla "balance"
        $sql = "UPDATE balance SET efectivo = ? WHERE cliente_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $nuevo_efectivo, $cliente_id);
        $stmt->execute();
        $stmt->close();

        // Insertar los datos en la tabla "cedear"
        $sql = "INSERT INTO cedear (ticker, cantidad, precio, fecha, cliente_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sidsi", $ticker_cedear, $cantidad_cedear, $precio_cedear, $fecha_cedear, $cliente_id);
        $stmt->execute();
        $stmt->close();

        // Redirigir al archivo cliente.php con el id del cliente
        header("Location: ../backend/cliente.php?cliente_id=$cliente_id#cedear");
        exit();
    } else {
        // Establecer mensaje de error de saldo insuficiente
        $error_msg = "Saldo insuficiente";
    }
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
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown"
                aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="../backend/lista_clientes.php"><i class="fa-solid fa-users me-2"></i>Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../backend/alta_clientes.php"><i class="fa-solid fa-user-plus me-2"></i>Alta Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../backend/historial.php"><i class="fa-solid fa-clock-rotate-left me-2"></i>Historial
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php"><i class="fa-solid fa-power-off me-2"></i>Salir
                        </a>
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

        <!-- COMPRA CEDEAR -->
        <div class="col-3"></div>
        <div class="col-6 text-center">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Comprar CEDEAR</h5>
                <?php if ($error_msg): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_msg; ?>
                    </div>
                <?php endif; ?>
                <form id="compra_cedear" method="POST" action="">
                    <input type="hidden" name="cliente_id" value="<?php echo htmlspecialchars($cliente_id); ?>">
                    <!-- Saldo -->
                    <div class="row mb-3 align-items-center">
                        <label for="saldo" class="col-sm-2 col-form-label">Saldo</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-chart-line"></i></span>
                                <input type="text" class="form-control" id="saldo" name="saldo" value="<?php echo htmlspecialchars($saldo_formateado); ?>" readonly disabled>
                            </div>
                        </div>
                    </div>
                    <!-- Ticker -->
                    <div class="row mb-3 align-items-center">
                        <label for="ticker_cedear" class="col-sm-2 col-form-label">Ticker</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-chart-line"></i></span>
                                <input type="text" class="form-control" id="ticker_cedear" name="ticker" required autofocus>
                            </div>
                            <div id="tickerDropdown_cedear" class="dropdown-menu" style="display: none; width: 100%;"></div>
                        </div>
                    </div>
                    <!-- Cantidad -->
                    <div class="row mb-3 align-items-center">
                        <label for="cantidad" class="col-sm-2 col-form-label">Cantidad</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-hashtag"></i></span>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                            </div>
                        </div>
                    </div>
                    <!-- Precio -->
                    <div class="row mb-3 align-items-center">
                        <label for="precio" class="col-sm-2 col-form-label">Precio</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                            </div>
                        </div>
                    </div>
                    <!-- Fecha -->
                    <div class="row mb-3 align-items-center">
                        <label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-alt"></i></span>
                                <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $fecha_cedear_hoy; ?>" required>
                            </div>
                        </div>
                    </div>
                    <hr class="mod mb-3">
                    <!-- Botones -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-custom ver"><i class="fa-solid fa-check me-2"></i>Aceptar</button>
                        <button type="button" class="btn btn-custom eliminar"
                            onclick="window.location.href='../backend/cliente.php?cliente_id=<?php echo $cliente_id; ?>#cedear'">
                            <i class="fa-solid fa-times me-2"></i>Cancelar</a>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class=" col-3">
        </div>
        <!-- FIN COMPRA CEDEAR -->

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
    <script src="../js/tickers_cedear.js"></script>
    <!-- FIN JS -->
</body>

</html>