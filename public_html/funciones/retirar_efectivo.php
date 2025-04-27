<?php
require_once '../../config/config.php';
require_once '../funciones/formato_dinero.php';

$cliente_id = isset($_GET['cliente_id']) ? intval($_GET['cliente_id']) : 1;
$retiro_exitoso = false;

// Obtener datos del cliente
$sql = "SELECT nombre, apellido FROM clientes WHERE cliente_id = :cliente_id";
$stmt = $conexion->prepare($sql);
$stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
$stmt->execute();
$cliente = $stmt->fetch();

if (!$cliente) {
    die("Cliente no encontrado.");
}

$nombre = $cliente['nombre'];
$apellido = $cliente['apellido'];

// Obtener saldo actual
$sql_saldo = "SELECT efectivo FROM balance WHERE cliente_id = :cliente_id";
$stmt_saldo = $conexion->prepare($sql_saldo);
$stmt_saldo->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
$stmt_saldo->execute();
$saldo_row = $stmt_saldo->fetch();
$saldo_en_pesos = $saldo_row ? floatval($saldo_row['efectivo']) : 0;
$saldo_en_pesos_formateado = formatear_dinero($saldo_en_pesos);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $monto = isset($_POST['monto']) ? str_replace(',', '.', $_POST['monto']) : '0.00';
    $monto = floatval($monto);

    if (!is_numeric($monto) || $monto <= 0) {
        echo "<script>alert('El monto debe ser mayor a cero.');</script>";
    } elseif ($monto > $saldo_en_pesos) {
        echo "<script>alert('No puede retirar un monto mayor al saldo disponible.');</script>";
    } else {
        $nuevo_saldo = $saldo_en_pesos - $monto;

        $sql_update = "UPDATE balance SET efectivo = :nuevo_saldo WHERE cliente_id = :cliente_id";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bindParam(':nuevo_saldo', $nuevo_saldo);
        $stmt_update->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);

        if ($stmt_update->execute()) {
            $retiro_exitoso = true;
        } else {
            echo "<script>alert('Error al realizar el retiro');</script>";
        }
    }
}

$nombre_y_apellido = htmlspecialchars($nombre . ' ' . $apellido);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goodfellas Inc.</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="../img/logo.png" alt="Logo" title="GoodFellas" /></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="../backend/lista_clientes.php"><i class="fa-solid fa-users me-2"></i>Clientes</a></li>
                    <li class="nav-item"><a class="nav-link" href="../backend/alta_clientes.php"><i class="fa-solid fa-user-plus me-2"></i>Alta Clientes</a></li>
                    <li class="nav-item"><a class="nav-link" href="../backend/dolares.php"><i class="fa-solid fa-dollar-sign me-2"></i>Dólares</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fa-solid fa-power-off me-2"></i>Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- FIN NAVBAR -->

    <!-- CONTENIDO -->
    <div class="row mx-2 mt-navbar">

        <!-- TITULO -->
        <div class="col-12 text-center">
            <h4 class="fancy"><?php echo $nombre_y_apellido; ?></h4>
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <!-- RETIRAR EFECTIVO -->
        <div class="col-4"></div>
        <div class="col-md-12 col-lg-4 text-center">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Retirar Efectivo</h5>
                <form method="POST" action="">
                    <input type="hidden" name="cliente_id" value="<?php echo $cliente_id; ?>">
                    <!-- Saldo -->
                    <div class="row mb-3 align-items-center">
                        <label for="saldo" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Saldo</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-chart-line"></i></span>
                                <input type="text" class="form-control" value="$ <?php echo $saldo_en_pesos_formateado; ?>" readonly disabled>
                            </div>
                        </div>
                    </div>
                    <!-- Monto -->
                    <div class="row mb-3 align-items-center">
                        <label for="monto" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Monto</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                <input type="text" step="0.01" class="form-control" id="monto" name="monto" placeholder="0,00" autofocus required>
                            </div>
                        </div>
                    </div>
                    <hr class="mod mb-3">
                    <!-- Botones -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-custom ver"><i class="fa-solid fa-check me-2"></i>Aceptar</button>
                        <button type="button" class="btn btn-custom eliminar" onclick="window.location.href='../backend/lista_clientes.php'">
                            <i class="fa-solid fa-times me-2"></i>Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-4"></div>
        <!-- FIN RETIRAR EFECTIVO -->

    </div>
    <!-- FIN CONTENIDO -->

    <!-- MODAL -->
    <?php if ($retiro_exitoso): ?>
        <div class="modal fade" id="modalExito" tabindex="-1" aria-labelledby="modalExitoLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title w-100 text-white" id="modalExitoLabel">Transacción completada</h5>
                    </div>
                    <div class="modal-body">
                        <p>El retiro fue realizado correctamente.</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <a href="../backend/lista_clientes.php" class="btn btn-custom ver">
                            <i class="fa-solid fa-check me-2"></i>Aceptar</a>
                    </div>
                </div>
            </div>
        </div>
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('modalExito'));
                modal.show();
            });
        </script>
    <?php endif; ?>
    <!-- FIN MODAL -->

    <!-- FOOTER -->
    <footer class="footer bg-light">
        <a href="https://www.afip.gob.ar/" target="_blank" rel="noopener noreferrer">
            <img id="fixed-image" src="../img/chorro.png" alt="" data-bs-toggle="tooltip" data-bs-placement="top" title="Puede darme dinero?" />
        </a>
        <div class="container">
            <span class="text-muted">© GoodFellas Inc.</span>
        </div>
    </footer>
    <!-- FIN FOOTER -->

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="../js/tooltip.js"></script>
    <script src="../js/easter_egg.js"></script>
    <!-- FIN JS -->

</body>

</html>