<?php
// Incluir archivo de configuración
require_once '../../config/config.php';

// Obtener el id del cliente y el ticker desde la URL
$cliente_id = isset($_GET['cliente_id']) ? $_GET['cliente_id'] : 1;
$ticker_acciones = isset($_GET['ticker']) ? $_GET['ticker'] : '';

// Consulta para obtener los datos del cliente
$sql = "SELECT nombre, apellido FROM clientes WHERE cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$stmt->bind_result($nombre, $apellido);
$stmt->fetch();
$stmt->close();

// Consulta para obtener los valores de cantidad, precio y id de la tabla acciones
$sql = "SELECT id, cantidad, precio, fecha FROM acciones WHERE ticker = ? AND cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $ticker_acciones, $cliente_id);
$stmt->execute();
$stmt->bind_result($id_accion, $cantidad_acciones_accion, $precio_acciones_accion, $fecha_acciones_accion);
$stmt->fetch();
$stmt->close();

// Obtener la fecha de hoy (por si el usuario no selecciona una)
$fecha_acciones_hoy = date('Y-m-d');

// Inicializar el mensaje de error
$error_msg = '';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $accion_id = $_POST['accion_id'];  // Se obtiene el ID de la acción
    $nuevo_cantidad = $_POST['cantidad'];
    $nuevo_precio = $_POST['precio'];
    $fecha_acciones = $_POST['fecha'];

    // Calcular el costo de compra
    $nuevo_precio_compra = $nuevo_cantidad * $nuevo_precio;

    // Obtener el valor actual de efectivo del cliente
    $sql = "SELECT efectivo FROM balance WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $stmt->bind_result($efectivo);
    $stmt->fetch();
    $stmt->close();

    // Ajustar el saldo del cliente según la diferencia de inversión
    $precio_acciones_compra_original = $cantidad_acciones_accion * $precio_acciones_accion;
    if ($precio_acciones_compra_original > $nuevo_precio_compra) {
        $diferencia = $precio_acciones_compra_original - $nuevo_precio_compra;
        $nuevo_efectivo = $efectivo + $diferencia;
        $actualizar_efectivo = true;
    } else {
        $diferencia = $nuevo_precio_compra - $precio_acciones_compra_original;
        if ($efectivo >= $diferencia) {
            $nuevo_efectivo = $efectivo - $diferencia;
            $actualizar_efectivo = true;
        } else {
            $error_msg = "Saldo insuficiente";
            $actualizar_efectivo = false;
        }
    }

    if ($actualizar_efectivo) {
        // Actualizar el efectivo en la tabla "balance"
        $sql = "UPDATE balance SET efectivo = ? WHERE cliente_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $nuevo_efectivo, $cliente_id);
        $stmt->execute();
        $stmt->close();

        // Actualizar solo la acción específica con el ID correspondiente
        $sql = "UPDATE acciones SET cantidad = ?, precio = ?, fecha = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dssi", $nuevo_cantidad, $nuevo_precio, $fecha_acciones, $accion_id);
        $stmt->execute();
        $stmt->close();

        // Redirigir al perfil del cliente
        header("Location: ../backend/cliente.php?cliente_id=$cliente_id#acciones");
        exit();
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
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

        <!-- EDITAR ACCIONES -->
        <div class="col-3"></div>
        <div class="col-6 text-center">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Editar los datos de <?php echo htmlspecialchars($ticker_acciones); ?></h5>
                <?php if ($error_msg): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_msg; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="">
                    <input type="hidden" name="accion_id" value="<?php echo htmlspecialchars($id_accion); ?>">
                    <!-- Ticker 
                    <div class="row mb-3 align-items-center">
                        <label for="ticker" class="col-sm-2 col-form-label">Ticker</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-chart-line"></i></span>
                                <input type="text" class="form-control" id="ticker" name="ticker" value="<?php echo htmlspecialchars($ticker_acciones); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    -->
                    <!-- Cantidad -->
                    <div class="row mb-3 align-items-center">
                        <label for="cantidad" class="col-sm-2 col-form-label">Cantidad</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-hashtag"></i></span>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($cantidad_acciones_accion); ?>" autofocus required>
                            </div>
                        </div>
                    </div>
                    <!-- Precio -->
                    <div class="row mb-3 align-items-center">
                        <label for="precio" class="col-sm-2 col-form-label">Precio</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                <input type="text" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo htmlspecialchars($precio_acciones_accion); ?>" placeholder="0,00" required>
                            </div>
                        </div>
                    </div>
                    <!-- Fecha -->
                    <div class="row mb-3 align-items-center">
                        <label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-alt"></i></span>
                                <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo htmlspecialchars($fecha_acciones_accion); ?>" required>
                            </div>
                        </div>
                    </div>
                    <hr class="mod mb-3">
                    <!-- Botones -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-custom ver"><i class="fa-solid fa-check me-2"></i>Aceptar</button>
                        <a href="../backend/cliente.php?cliente_id=<?php echo $cliente_id; ?>#acciones"
                            class="btn btn-custom eliminar"><i class="fa-solid fa-times me-2"></i>Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-3"></div>
        <!-- FIN EDITAR ACCIONES -->

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
    <script src="../js/tickers_acciones.js"></script>
    <!-- FIN JS -->
</body>

</html>