<?php
require_once '../../config/config.php';
require_once '../funciones/formato_dinero.php';

$cliente_id = isset($_GET['cliente_id']) ? intval($_GET['cliente_id']) : 1;

// Manejo POST - Ingreso de efectivo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $sql_saldo = "SELECT efectivo FROM balance WHERE cliente_id = :cliente_id";
    $stmt_saldo = $conexion->prepare($sql_saldo);
    $stmt_saldo->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
    $stmt_saldo->execute();
    $saldo_row = $stmt_saldo->fetch();

    $saldo_en_pesos = $saldo_row ? floatval($saldo_row['efectivo']) : 0;
    $monto_ingresado = floatval(str_replace(',', '.', $_POST['monto'] ?? 0));

    if ($monto_ingresado <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'El monto debe ser mayor a cero.']);
        exit;
    }

    $nuevo_saldo = $saldo_en_pesos + $monto_ingresado;

    $sql_update = "UPDATE balance SET efectivo = :nuevo_saldo WHERE cliente_id = :cliente_id";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bindParam(':nuevo_saldo', $nuevo_saldo);
    $stmt_update->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);

    if ($stmt_update->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al realizar la operación.']);
    }
    exit;
}

// Modo GET - Página
$sql = "SELECT nombre, apellido FROM clientes WHERE cliente_id = :cliente_id";
$stmt = $conexion->prepare($sql);
$stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
$stmt->execute();
$cliente = $stmt->fetch();

if (!$cliente) die("Cliente no encontrado.");

$nombre = $cliente['nombre'];
$apellido = $cliente['apellido'];

$sql_saldo = "SELECT efectivo FROM balance WHERE cliente_id = :cliente_id";
$stmt_saldo = $conexion->prepare($sql_saldo);
$stmt_saldo->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
$stmt_saldo->execute();
$saldo_row = $stmt_saldo->fetch();

$saldo_en_pesos = $saldo_row ? floatval($saldo_row['efectivo']) : 0;
$saldo_en_pesos_formateado = formatear_dinero($saldo_en_pesos);
$nombre_y_apellido = htmlspecialchars($nombre . ' ' . $apellido);
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

    <!-- CONTENIDO -->
    <div class="row mx-2 mt-navbar">
        <div class="col-12 text-center">
            <h4 class="fancy"><?= $nombre_y_apellido ?></h4>
        </div>
        <hr class="mod">

        <!-- INGRESAR EFECTIVO -->
        <div class="col-4"></div>
        <div class="col-4 text-center">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Ingresar Efectivo</h5>
                <form id="form-ingresar-efectivo" method="POST" data-cliente-id="<?= $cliente_id ?>">
                    <input type="hidden" name="cliente_id" value="<?= $cliente_id ?>">
                    <!-- Saldo -->
                    <div class="row mb-3 align-items-center">
                        <label for="saldo" class="col-sm-2 col-form-label">Saldo</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-chart-line"></i></span>
                                <input type="text" class="form-control" value="$ <?= $saldo_en_pesos_formateado ?>" readonly disabled>
                            </div>
                        </div>
                    </div>
                    <!-- Monto -->
                    <div class="row mb-3 align-items-center">
                        <label for="monto" class="col-sm-2 col-form-label">Monto</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                <input type="text" step="0.01" class="form-control" id="monto" name="monto" placeholder="0,00" autofocus required>
                            </div>
                        </div>
                    </div>
                    <hr class="mod mb-3">
                    <!-- Botones -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-custom ver"><i class="fa-solid fa-check me-2"></i>Aceptar</button>
                        <button type="button" class="btn btn-custom eliminar" onclick="window.location.href='../backend/lista_clientes.php'">
                            <i class="fa-solid fa-times me-2"></i>Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-4"></div>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="modalExito" tabindex="-1" aria-labelledby="modalExitoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header bg-success">
                    <h5 class="modal-title w-100 text-white" id="modalExitoLabel">Transacción completada</h5>
                </div>
                <div class="modal-body">
                    <p>El ingreso fue realizado correctamente.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <a href="../backend/lista_clientes.php" class="btn btn-custom ver">
                        <i class="fa-solid fa-check me-2"></i>Aceptar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer bg-light">
        <a href="https://www.afip.gob.ar/" target="_blank" rel="noopener noreferrer">
            <img id="fixed-image" src="../img/chorro.png" alt="" data-bs-toggle="tooltip" title="Puede darme dinero?" />
        </a>
        <div class="container">
            <span class="text-muted">© GoodFellas Inc.</span>
        </div>
    </footer>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-ingresar-efectivo');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    const cliente_id = form.dataset.clienteId;

                    fetch(`ingresar_efectivo.php?cliente_id=${cliente_id}`, {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                const modal = new bootstrap.Modal(document.getElementById('modalExito'));
                                modal.show();
                            } else {
                                showAlert(data.message || 'Error al ingresar efectivo', 'danger');
                            }
                        })
                        .catch(() => {
                            showAlert('Error de red o servidor', 'danger');
                        });
                });
            }

            function showAlert(message, type = 'danger') {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show text-center`;
                alertDiv.style.position = 'fixed';
                alertDiv.style.top = '20px';
                alertDiv.style.left = '50%';
                alertDiv.style.transform = 'translateX(-50%)';
                alertDiv.style.zIndex = '9999';
                alertDiv.style.minWidth = '300px';
                alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.body.appendChild(alertDiv);
                setTimeout(() => alertDiv.remove(), 4000);
            }
        });
    </script>
</body>

</html>