<?php
require_once '../../config/config.php';
require_once '../funciones/cliente_funciones.php';
require_once '../funciones/formato_dinero.php';

// Obtener el ID del cliente y el ticker desde GET
$cliente_id = isset($_GET['cliente_id']) ? intval($_GET['cliente_id']) : 1;
$ticker = isset($_GET['ticker']) ? $_GET['ticker'] : '';

// Obtener los datos actuales del CEDEAR
$sql = "SELECT ticker_cedear, cantidad_cedear, fecha_cedear, precio_cedear, ccl_compra_cedear 
        FROM cedear 
        WHERE cliente_id = ? AND ticker_cedear = ?";
$stmt = $conexion->prepare($sql);
$stmt->execute([$cliente_id, $ticker]);
$cedear = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cedear) {
    echo "<script>alert('No se encontró el CEDEAR.'); window.history.back();</script>";
    exit;
}

$db_ticker = $cedear['ticker_cedear'];
$db_cantidad = $cedear['cantidad_cedear'];
$db_fecha_compra = $cedear['fecha_cedear'];
$db_precio_compra = $cedear['precio_cedear'];
$db_ccl_compra = $cedear['ccl_compra_cedear'];
$cantidad_max = $db_cantidad - 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cantidad_vendida = (int) $_POST['cantidad'];
    $precio_venta = (float) $_POST['precio_venta'];
    $fecha_venta = $_POST['fecha_venta'];
    $ccl_venta = $_POST['ccl_venta'];

    // Formatear CCL venta
    $ccl_venta = str_replace('.', '', $ccl_venta);
    $ccl_venta = str_replace(',', '.', $ccl_venta);
    $ccl_venta = floatval($ccl_venta);

    if ($cantidad_vendida < $db_cantidad) {
        // Iniciar transacción
        $conexion->beginTransaction();

        try {
            // Actualizar cantidad en tabla CEDEAR
            $nueva_cantidad = $db_cantidad - $cantidad_vendida;
            $sql_update = "UPDATE cedear SET cantidad_cedear = ? WHERE cliente_id = ? AND ticker_cedear = ?";
            $stmt = $conexion->prepare($sql_update);
            $stmt->execute([$nueva_cantidad, $cliente_id, $ticker]);

            // Sumar venta al efectivo
            $total_venta = $cantidad_vendida * $precio_venta;
            $sql_balance = "UPDATE balance SET efectivo = efectivo + ? WHERE cliente_id = ?";
            $stmt = $conexion->prepare($sql_balance);
            $stmt->execute([$total_venta, $cliente_id]);

            // Insertar en historial de CEDEAR
            $sql_historial = "INSERT INTO cedear_historial (
                cliente_id, ticker_cedear, cantidad_cedear, fecha_compra_cedear, 
                precio_compra_cedear, ccl_compra, fecha_venta_cedear, precio_venta_cedear, ccl_venta
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql_historial);
            $stmt->execute([
                $cliente_id,
                $db_ticker,
                $cantidad_vendida,
                $db_fecha_compra,
                $db_precio_compra,
                $db_ccl_compra,
                $fecha_venta,
                $precio_venta,
                $ccl_venta
            ]);

            $conexion->commit();

            header("Location: ../backend/cliente.php?cliente_id=$cliente_id#cedears");
            exit;
        } catch (Exception $e) {
            $conexion->rollBack();
            echo "<script>alert('Ocurrió un error al procesar la venta de CEDEAR.');</script>";
        }
    } else {
        echo "<script>alert('Cantidad máxima para una venta parcial = $cantidad_max');</script>";
    }
}

// Obtener datos del cliente
$sql_cliente = "SELECT nombre, apellido FROM clientes WHERE cliente_id = ?";
$stmt = $conexion->prepare($sql_cliente);
$stmt->execute([$cliente_id]);
$cliente = $stmt->fetch();

$nombre_y_apellido = htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']);

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
                        <a class="nav-link active" href="../backend/lista_clientes.php"><i class="fa-solid fa-users me-2"></i>Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../backend/alta_clientes.php"><i class="fa-solid fa-user-plus me-2"></i>Alta Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../backend/dolares.php"><i class="fa-solid fa-dollar-sign me-2"></i>Dólares</a>
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
            <h4 class="fancy"><?php echo $nombre_y_apellido; ?></h4>
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <!-- VENTA PARCIAL ACCIONES -->
        <div class="col-4"></div>
        <div class="col-md-12 col-lg-4 text-center">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Venta parcial de <?php echo htmlspecialchars($db_ticker); ?></h5>
                <form id="venta_parcial" method="POST" action="">
                    <input type="hidden" name="cliente_id" value="<?php echo $cliente_id; ?>">
                    <input type="hidden" name="ticker" value="<?php echo $db_ticker; ?>">

                    <!-- Cantidad -->
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end" for="cantidad">Cantidad</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fa-solid fa-hashtag"></i></span>
                                        <input type="text" placeholder="Máximo <?php echo htmlspecialchars($cantidad_max); ?> Cedears" class="form-control"
                                            id="cantidad" name="cantidad" value="" autofocus required>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Fin Cantidad -->

                    <!-- Precio Venta -->
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end" for="precio_venta">Precio Venta</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                        <input type="text" class="form-control" id="precio_venta" name="precio_venta"
                                            placeholder="0,00" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Fin Precio Venta -->

                    <!-- CCL Venta -->
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end" for="ccl_venta">Dólar CCL</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                        <input type="text" class="form-control" id="ccl_venta" name="ccl_venta"
                                            value="<?php echo formatear_dinero($promedio_ccl); ?>" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Fin CCL Venta -->

                    <!-- Fecha Venta -->
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end" for="fecha_venta">Fecha Venta</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-alt"></i></span>
                                        <input type="date" class="form-control" id="fecha_venta" name="fecha_venta"
                                            value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fin Fecha Venta -->

                    <hr class="mod mb-3">

                    <!-- Botones -->
                    <div class="text-center">
                        <button type="submit" id="btnAceptar" class="btn btn-custom ver"><i class="fa-solid fa-check me-2"></i>Aceptar</button>
                        <a href="../backend/cliente.php?cliente_id=<?php echo $cliente_id; ?>#cedears"
                            class="btn btn-custom eliminar"><i class="fa-solid fa-times me-2"></i>Cancelar</a>
                    </div>
                    <!-- Fin Botones -->
                </form>
            </div>
        </div>
        <div class="col-4"></div>
        <!-- FIN VENTA PARCIAL ACCIONES -->

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
    <script>
        document.getElementById('btnAceptar').addEventListener('click', function() {
            var cantidad = parseFloat(document.getElementById('cantidad').value);
            var cantidadMax = <?php echo $cantidad_max; ?>;

            if (cantidad >= cantidadMax) {
                alert('Cantidad máxima de Cedears para una\nventa parcial = ' + cantidadMax);
            } else {
                document.getElementById('venta_parcial').submit();
            }
        });
    </script>
    <!-- FIN JS -->
</body>

</html>