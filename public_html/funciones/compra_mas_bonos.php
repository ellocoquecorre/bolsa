<?php
// Incluir archivo de configuración
require_once '../../config/config.php';
require_once '../funciones/formato_dinero.php';
require_once '../funciones/cliente_funciones.php';

$error_msg = '';

$cliente_id = isset($_GET['cliente_id']) ? intval($_GET['cliente_id']) : 1;
$ticker = isset($_GET['ticker']) ? $_GET['ticker'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cantidad_hoy = isset($_POST['cantidad']) ? floatval($_POST['cantidad']) : 0;
    $precio_hoy = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;
    $fecha_hoy = isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d');
    $ccl_compra = isset($_POST['ccl_compra']) ? $_POST['ccl_compra'] : '';

    // Formatear ccl_compra
    $ccl_compra = str_replace('.', '', $ccl_compra);
    $ccl_compra = str_replace(',', '.', $ccl_compra);
    $ccl_compra = floatval($ccl_compra);

    // Obtener saldo del cliente
    $sql_saldo = "SELECT efectivo FROM balance WHERE cliente_id = ?";
    $stmt = $conexion->prepare($sql_saldo);
    $stmt->execute([$cliente_id]);
    $saldo_en_pesos = $stmt->fetchColumn();

    $costo_total = $cantidad_hoy * $precio_hoy;

    if ($costo_total > $saldo_en_pesos) {
        $error_msg = "Saldo insuficiente";
    } else {
        // Buscar bono existente
        $sql = "SELECT cantidad_bonos, precio_bonos, ccl_compra FROM bonos WHERE cliente_id = ? AND ticker_bonos = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$cliente_id, $ticker]);
        $bono = $stmt->fetch();

        $db_cantidad = $bono ? $bono['cantidad_bonos'] : 0;
        $db_precio = $bono ? $bono['precio_bonos'] : 0;
        $db_ccl = $bono ? $bono['ccl_compra'] : 0;

        // Cálculo de precios promedios
        $nuevo_precio = (($db_cantidad * $db_precio) + ($cantidad_hoy * $precio_hoy)) / ($db_cantidad + $cantidad_hoy);
        $nuevo_ccl_compra = (($db_cantidad * $db_ccl) + ($cantidad_hoy * $ccl_compra)) / ($db_cantidad + $cantidad_hoy);

        if ($bono) {
            // Actualizar bono existente
            $sql_update = "UPDATE bonos 
                           SET cantidad_bonos = cantidad_bonos + ?, 
                               precio_bonos = ?, 
                               ccl_compra = ?, 
                               fecha_bonos = ? 
                           WHERE cliente_id = ? AND ticker_bonos = ?";
            $stmt = $conexion->prepare($sql_update);
            $stmt->execute([$cantidad_hoy, $nuevo_precio, $nuevo_ccl_compra, $fecha_hoy, $cliente_id, $ticker]);
        } else {
            // Insertar nuevo bono
            $sql_insert = "INSERT INTO bonos (cliente_id, ticker_bonos, cantidad_bonos, precio_bonos, ccl_compra, fecha_bonos) 
                           VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql_insert);
            $stmt->execute([$cliente_id, $ticker, $cantidad_hoy, $precio_hoy, $ccl_compra, $fecha_hoy]);
        }

        // Actualizar saldo
        $nuevo_saldo = $saldo_en_pesos - $costo_total;
        $sql_balance = "UPDATE balance SET efectivo = ? WHERE cliente_id = ?";
        $stmt = $conexion->prepare($sql_balance);
        $stmt->execute([$nuevo_saldo, $cliente_id]);

        // Redireccionar
        header("Location: ../backend/cliente.php?cliente_id=$cliente_id#bonos");
        exit();
    }
}

// Obtener datos del cliente
$sql_cliente = "SELECT nombre, apellido FROM clientes WHERE cliente_id = ?";
$stmt = $conexion->prepare($sql_cliente);
$stmt->execute([$cliente_id]);
$cliente = $stmt->fetch();

$nombre_y_apellido = htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']);

// Obtener saldo para mostrar
$sql_saldo = "SELECT efectivo FROM balance WHERE cliente_id = ?";
$stmt = $conexion->prepare($sql_saldo);
$stmt->execute([$cliente_id]);
$saldo_en_pesos = $stmt->fetchColumn();
$saldo_en_pesos_formateado = formatear_dinero($saldo_en_pesos);
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
        <div class="col-6 text-center">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Comprar más bonos de <?php echo htmlspecialchars($ticker); ?></h5>
                <?php if ($error_msg): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_msg; ?>
                    </div>
                <?php endif; ?>
                <form id="compra_bonos" method="POST" action="">
                    <input type="hidden" name="cliente_id" value="<?php echo $cliente_id; ?>">

                    <!-- Saldo -->
                    <div class="row mb-3 align-items-center">
                        <label for="saldo" class="col-sm-2 col-form-label">Saldo</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-chart-line"></i></span>
                                <input type="text" class="form-control" id="saldo" name="saldo"
                                    value="$ <?php echo $saldo_en_pesos_formateado; ?>" readonly disabled>
                            </div>
                        </div>
                    </div>
                    <!-- Fin Saldo -->

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
                    <!-- Fin Cantidad -->

                    <!-- Precio -->
                    <div class="row mb-3 align-items-center">
                        <label for="precio" class="col-sm-2 col-form-label">Precio</label>
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
                        <label for="ccl_compra" class="col-sm-2 col-form-label">Dólar CCL</label>
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
                        <label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-alt"></i></span>
                                <input type="date" class="form-control" id="fecha" name="fecha"
                                    value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                    </div>

                    <hr class="mod mb-3">

                    <!-- Botones -->
                    <div class="text-end">
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
    <script src="../js/tooltip.js"></script>
    <script src="../js/easter_egg.js"></script>
    <!-- FIN JS -->
</body>

</html>