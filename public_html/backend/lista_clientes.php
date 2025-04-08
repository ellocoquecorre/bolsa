<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../config/config.php';
require_once '../funciones/formato_dinero.php';

$clientes = [];

try {
    // Obtener clientes
    $sql = "SELECT cliente_id, nombre, apellido, email, telefono, corredora, url FROM clientes";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll();

    // Iterar por cada cliente
    foreach ($resultado as $row) {
        $cliente_id = $row['cliente_id'];

        // Obtener saldo asociado
        $balance_sql = "SELECT efectivo FROM balance WHERE cliente_id = :cliente_id";
        $balance_stmt = $conexion->prepare($balance_sql);
        $balance_stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $balance_stmt->execute();
        $balance_row = $balance_stmt->fetch();

        $row['efectivo'] = $balance_row ? $balance_row['efectivo'] : null;
        $clientes[] = $row;
    }
} catch (PDOException $e) {
    error_log("Error al obtener los clientes: " . $e->getMessage());
    die("Hubo un problema al cargar los datos de los clientes.");
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle na[...]
                <span class=" navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="lista_clientes.php"><i class="fa-solid fa-users me-2"></i>Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="alta_clientes.php"><i class="fa-solid fa-user-plus me-2"></i>Alta Clientes</a>
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
            <h4 class="fancy">Clientes</h4>
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <!-- LISTA CLIENTES -->
        <div class="col-2"></div>
        <div class="col-8 text-center">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Listado de clientes</h5>
                <div class="table-responsive">
                    <table id="clientes" class="table table-bordered table-striped">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>Cliente</th>
                                <th>Saldo</th>
                                <th>Mail</th>
                                <th>Teléfono</th>
                                <th>Corredora</th>
                                <th colspan="6" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientes as $row): ?>
                                <tr>
                                    <td class="text-left"><?php echo htmlspecialchars($row['nombre'] . ' ' . $row['apellido']); ?></td>
                                    <td class="text-right">$ <?php echo htmlspecialchars(formatear_dinero($row['efectivo'])); ?></td>
                                    <td class="text-right"><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td class="text-right"><?php echo htmlspecialchars($row['telefono']); ?></td>
                                    <td>
                                        <a href="<?php echo htmlspecialchars($row['url']); ?>" target="_blank" class="cliente-link">
                                            <i class="fa-regular fa-hand-pointer me-2"></i><?php echo htmlspecialchars($row['corredora']); ?>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown d-flex justify-content-center">
                                            <button class="btn custom-btn dropdown-toggle" type="button" id="dropdownMenuButton"
                                                data-bs-toggle="dropdown" aria-expanded="false" title="Acciones">
                                                <i class="fa-solid fa-bars"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <a class="dropdown-item" href="cliente.php?cliente_id=<?php echo $row['cliente_id']; ?>">
                                                        <i class="fa-solid fa-magnifying-glass me-2"></i>Ver cliente</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="historial.php?cliente_id=<?php echo $row['cliente_id']; ?>">
                                                        <i class="fa-solid fa-hourglass me-2"></i>Historial</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="../funciones/ingresar_efectivo.php?cliente_id=<?php echo $row['cliente_id']; ?>">
                                                        <i class="fa-solid fa-plus me-2"></i>Ingresar Efectivo</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="../funciones/retirar_efectivo.php?cliente_id=<?php echo $row['cliente_id']; ?>">
                                                        <i class="fa-solid fa-minus me-2"></i>Retirar Efectivo</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="../funciones/editar_cliente.php?cliente_id=<?php echo $row['cliente_id']; ?>">
                                                        <i class="fa-solid fa-pen-to-square me-2"></i>Editar cliente</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item eliminar text-danger" href="#" data-cliente-id="<?php echo $row['cliente_id']; ?>">
                                                        <i class="fa-regular fa-trash-can me-2"></i>Eliminar cliente
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-2"></div>
        <!-- FIN LISTA CLIENTES -->

    </div>
    <!-- FIN CONTENIDO -->

    <!-- MODAL ELIMINACIÓN -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">¿Estás seguro que querés eliminar este cliente permanentemente?</p>
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Se van a eliminar todos los datos relacionados con este cliente y esta acción no se puede deshacer.<br><b>Fijate que hacés.</b>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-custom cancelar" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fa-solid fa-check me-2"></i>Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN MODAL ELIMINACIÓN -->

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
    <script src="../js/eliminar_cliente.js"></script>
    <script src="../js/tooltip.js"></script>
    <script src="../js/easter_egg.js"></script>
    <!-- FIN JS -->

</body>

</html>