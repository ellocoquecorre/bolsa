<?php
// 1. Verificar sesión y permisos
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}

// 2. Cargar configuración de conexión
require_once '../../config/config.php';

// 3. Crear conexión a la base de datos
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// 4. Obtener datos de clientes
$clientes = array();
$sql = "SELECT * FROM clientes";
$result = $conn->query($sql);

if ($result) {
    // Almacenamos todos los resultados en un array antes de cerrar
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }
    $result->free(); // Liberamos los resultados
}

// 5. Cerrar conexión
$conn->close();
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
    <!-- PRELOADER -->
    <div class="preloader" id="preloader">
        <div class="preloader-content">
            <img src="../img/preloader.gif" alt="Preloader" class="preloader-img">
        </div>
    </div>
    <!-- FIN PRELOADER -->

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
            <h4 class="fancy">Lista de Clientes</h4>
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <!-- LISTA CLIENTES -->
        <div class="col-12 text-center">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Listado de clientes</h5>
                <div class="table-responsive">
                    <table id="clientes" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Mail</th>
                                <th>Teléfono</th>
                                <th>Corredora</th>
                                <th colspan="6" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientes as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                                    <td><?php echo htmlspecialchars($row['corredora']); ?></td>
                                    <td class="text-center">
                                        <a href="cliente.php?cliente_id=<?php echo $row['cliente_id']; ?>" class="btn btn-info btn-custom ver"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Ver">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="historial.php?cliente_id=<?php echo $row['cliente_id']; ?>" class="btn btn-info btn-custom ver"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Historial">
                                            <i class="fa-solid fa-hourglass"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="../funciones/ingresar_efectivo.php?cliente_id=<?php echo $row['cliente_id']; ?>" class="btn btn-info btn-custom ver"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Ingresar Efectivo">
                                            <i class="fa-solid fa-plus"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="../funciones/retirar_efectivo.php?cliente_id=<?php echo $row['cliente_id']; ?>" class="btn btn-info btn-custom retirar"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Retirar Efectivo">
                                            <i class="fa-solid fa-minus"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="../funciones/editar_cliente.php?cliente_id=<?php echo $row['cliente_id']; ?>" class="btn btn-info btn-custom editar"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-custom eliminar" data-cliente-id="<?php echo $row['cliente_id']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- FIN LISTA CLIENTES -->

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
    <script src="../js/eliminar_cliente.js"></script>
    <script src="../js/preloader.js"></script>
    <!-- FIN JS -->

</body>

</html>