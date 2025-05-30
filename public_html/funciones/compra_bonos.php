<?php
// Incluir archivo de configuración
require_once '../../config/config.php';
require_once '../funciones/formato_dinero.php';
require_once '../funciones/cliente_funciones.php';

// Obtener el id del cliente desde la URL
$cliente_id = isset($_GET['cliente_id']) ? intval($_GET['cliente_id']) : 1;

// Obtener los datos del cliente
$sql = "SELECT nombre, apellido FROM clientes WHERE cliente_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->execute([$cliente_id]);
$cliente = $stmt->fetch();

$nombre = $cliente['nombre'] ?? '';
$apellido = $cliente['apellido'] ?? '';
$nombre_y_apellido = htmlspecialchars(trim($nombre . ' ' . $apellido));

// Obtener el saldo en pesos del cliente
$sql_saldo = "SELECT efectivo FROM balance WHERE cliente_id = ?";
$stmt_saldo = $conexion->prepare($sql_saldo);
$stmt_saldo->execute([$cliente_id]);
$saldo_data = $stmt_saldo->fetch();
$saldo_en_pesos = $saldo_data['efectivo'] ?? 0;
$saldo_en_pesos_formateado = formatear_dinero($saldo_en_pesos);

// Obtener la fecha actual
$fecha_hoy = date('Y-m-d');

// Inicializar el mensaje de error
$error_msg = '';

// Si se envió el formulario, realizar las operaciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cantidad = floatval($_POST['cantidad']);
    $precio = floatval(str_replace(',', '.', $_POST['precio']));
    $ticker = $_POST['ticker'];
    $fecha = $_POST['fecha'];
    $ccl_compra = str_replace(',', '.', str_replace('.', '', $_POST['ccl_compra'])); // Formatear el valor de ccl_compra

    $total_compra = $cantidad * $precio;

    if ($total_compra > $saldo_en_pesos) {
        $error_msg = "Saldo insuficiente";
    } else {
        // Restar el valor de la compra al saldo en pesos
        $nuevo_saldo = $saldo_en_pesos - $total_compra;
        $sql_update_saldo = "UPDATE balance SET efectivo = ? WHERE cliente_id = ?";
        $stmt_update_saldo = $conexion->prepare($sql_update_saldo);
        $stmt_update_saldo->execute([$nuevo_saldo, $cliente_id]);

        // Insertar los datos de la compra en la tabla "bonos"
        $sql_insert_bono = "INSERT INTO bonos (cliente_id, ticker_bonos, cantidad_bonos, precio_bonos, fecha_bonos, ccl_compra)
                            VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert_bono = $conexion->prepare($sql_insert_bono);
        $stmt_insert_bono->execute([$cliente_id, $ticker, $cantidad, $precio, $fecha, $ccl_compra]);

        // Redirigir a la página del cliente
        header("Location: ../backend/cliente.php?cliente_id=$cliente_id#bonos");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                        <a class="nav-link active" href="../backend/lista_clientes.php"><i class="fa-solid fa-users me-2"></i>Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../backend/alta_clientes.php"><i class="fa-solid fa-user-plus me-2"></i>Alta Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../backend/dolares.php"><i class="fa-solid fa-dollar-sign me-2"></i>Dólares</a>
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
            <h4 class="fancy"><?php echo $nombre_y_apellido; ?></h4>
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <!-- COMPRA BONOS -->
        <div class="col-3"></div>
        <div class="col-md-12 col-lg-6 text-center">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Comprar Bonos</h5>
                <?php if ($error_msg): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_msg; ?>
                    </div>
                <?php endif; ?>
                <form id="compra_bonos" method="POST" action="">
                    <input type="hidden" name="cliente_id" value="<?php echo $cliente_id; ?>">

                    <!-- Saldo -->
                    <div class="row mb-3 align-items-center">
                        <label for="saldo" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Saldo</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-chart-line"></i></span>
                                <input type="text" class="form-control" id="saldo" name="saldo"
                                    value="$ <?php echo $saldo_en_pesos_formateado; ?>" readonly disabled>
                            </div>
                        </div>
                    </div>
                    <!-- Fin Saldo -->

                    <!-- Ticker -->
                    <div class="row mb-3 align-items-center">
                        <label for="ticker_bonos" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Ticker</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-chart-line"></i></span>
                                <input type="text" class="form-control" id="ticker" name="ticker" required autofocus>
                            </div>
                            <div id="tickerDropdown_bonos" class="dropdown-menu" style="display: none; width: 100%;"></div>
                        </div>
                    </div>
                    <!-- Fin Ticker -->

                    <!-- Cantidad -->
                    <div class="row mb-3 align-items-center">
                        <label for="cantidad" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Cantidad</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-hashtag"></i></span>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                            </div>
                        </div>
                    </div>
                    <!-- Fin Cantidad -->

                    <!-- Precio -->
                    <div class="row mb-3 align-items-center">
                        <label for="precio" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Precio</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                <input type="text" step="0.01" class="form-control" id="precio" name="precio"
                                    placeholder="0,00" required>
                            </div>
                        </div>
                    </div>
                    <!-- Fin Precio -->

                    <!-- CCL -->
                    <div class="row mb-3 align-items-center">
                        <label for="ccl_compra" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Dólar CCL</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                <input type="text" step="0.01" class="form-control" id="ccl_compra" name="ccl_compra"
                                    value="<?php echo formatear_dinero($promedio_ccl); ?>" required>
                            </div>
                        </div>
                    </div>
                    <!-- Fin CCL -->

                    <!-- Fecha -->
                    <div class="row mb-3 align-items-center">
                        <label for="fecha" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Fecha</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-alt"></i></span>
                                <input type="date" class="form-control" id="fecha" name="fecha"
                                    value="<?php echo $fecha_hoy; ?>" required>
                            </div>
                        </div>
                    </div>

                    <hr class="mod mb-3">

                    <!-- Botones -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-custom ver"><i class="fa-solid fa-check me-2"></i>Aceptar</button>
                        <button type="button" class="btn btn-custom eliminar"
                            onclick="window.location.href='../backend/cliente.php?cliente_id=<?php echo $cliente_id; ?>#bonos'">
                            <i class="fa-solid fa-times me-2"></i>Cancelar</button>
                    </div>
                    <!-- Fin Botones -->

                </form>
            </div>
        </div>
        <div class="col-3"></div>
        <!-- FIN COMPRA BONOS -->

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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="../js/tickers_bonos.js"></script>
    <script src="../js/tooltip.js"></script>
    <script src="../js/easter_egg.js"></script>
    <!-- FIN JS -->
</body>

</html>