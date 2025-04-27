<?php
// Incluir archivo de configuración
require_once '../../config/config.php';

// Obtener el id del cliente desde la URL
$cliente_id = isset($_GET['cliente_id']) ? intval($_GET['cliente_id']) : 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre     = $_POST['nombre'];
    $apellido   = $_POST['apellido'];
    $email      = $_POST['mail'];
    $telefono   = $_POST['telefono'];
    $corredora  = $_POST['corredora'];

    // Consulta para actualizar los datos del cliente
    $sql = "UPDATE clientes 
            SET nombre = :nombre, apellido = :apellido, email = :email, telefono = :telefono, corredora = :corredora 
            WHERE cliente_id = :cliente_id";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':corredora', $corredora);
    $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirigir a la lista de clientes después de actualizar
        header("Location: ../backend/lista_clientes.php");
        exit();
    } else {
        echo "Error al actualizar el registro.";
    }
} else {
    // Consulta para obtener los datos del cliente
    $sql = "SELECT nombre, apellido, email, telefono, corredora 
            FROM clientes 
            WHERE cliente_id = :cliente_id";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
    $stmt->execute();

    $cliente = $stmt->fetch();

    if ($cliente) {
        $nombre     = $cliente['nombre'];
        $apellido   = $cliente['apellido'];
        $email      = $cliente['email'];
        $telefono   = $cliente['telefono'];
        $corredora  = $cliente['corredora'];
    } else {
        echo "Cliente no encontrado.";
        exit;
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
    <style>

    </style>
    <!-- FIN CSS -->
</head>

<body>
    <!-- PRELOADER -->
    <div class="preloader d-flex justify-content-center align-items-center" id="preloader">
        <div class="preloader-content">
            <img src="../img/preloader.gif" alt="Preloader" class="preloader-img img-fluid">
        </div>
    </div>
    <!-- FIN PRELOADER -->

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
            <h4 class="fancy">Editar Cliente</h4>
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <!-- EDITAR CLIENTES -->
        <div class="col-3"></div>
        <div class="col-md-12 col-lg-6 text-center">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Edición de datos</h5>
                <form method="POST">
                    <div class="row mb-3 align-items-center">
                        <label for="nombre" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Nombre</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required autofocus>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label for="apellido" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Apellido</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($apellido); ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label for="mail" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Mail</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" class="form-control" id="mail" name="mail" value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label for="telefono" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Teléfono</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-phone"></i></span>
                                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label for="corredora" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Corredora</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-briefcase"></i></span>
                                <input type="text" class="form-control" id="corredora" name="corredora" value="<?php echo htmlspecialchars($corredora); ?>" required>
                            </div>
                        </div>
                    </div>
                    <hr class="mod mb-3">
                    <div class="text-center">
                        <button type="submit" class="btn btn-custom ver"><i class="fa-solid fa-check me-2"></i>Aceptar</button>
                        <a href="../backend/lista_clientes.php" class="btn btn-custom eliminar"><i class="fa-solid fa-xmark me-2"></i>Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-3"></div>
        <!-- FIN EDITAR CLIENTES -->

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
    <script src="../js/preloader.js"></script>
    <script src="../js/tooltip.js"></script>
    <script src="../js/easter_egg.js"></script>
    <!-- FIN JS -->
</body>

</html>