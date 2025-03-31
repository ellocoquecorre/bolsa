<?php
session_start();
require_once '../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Crear conexión a la base de datos
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("La conexión ha fallado: " . $conn->connect_error);
    }

    try {
        // Preparar y ejecutar la consulta para la tabla "admin"
        $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta para admin: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verificar la contraseña hasheada
            if (password_verify($password, $row['password'])) {
                // Si la contraseña es correcta
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['cliente_id'] = null; // Los administradores no tienen cliente_id
                header("Location: backend/lista_clientes.php"); // Redirigir a la lista de clientes
                exit;
            }
        }

        // Preparar y ejecutar la consulta para la tabla "clientes"
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE email = ?");
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta para clientes: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verificar la contraseña hasheada
            if (password_verify($password, $row['password'])) {
                // Si la contraseña es correcta
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['cliente_id'] = $row['cliente_id']; // Guardar cliente_id en la sesión
                header("Location: frontend/cliente.php?cliente_id=" . $row['cliente_id']); // Redirigir al cliente
                exit;
            }
        }

        // Si no se encuentra en ninguna tabla o la contraseña es incorrecta, mostrar un mensaje de error
        $error = "El correo electrónico o la contraseña son incorrectos.";
    } catch (Exception $e) {
        // Mostrar mensaje de error para depuración
        $error = $e->getMessage();
    }

    $stmt->close();
    $conn->close();
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
    <link rel="stylesheet" href="css/style.css">
    <!-- FIN CSS -->
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="img/logo.png" alt="Logo" title="GoodFellas" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <!-- NAVBAR -->

    <!-- CONTENIDO -->
    <div class="row mt-navbar mx-2">

        <!-- TITULO -->
        <div class="col-12 text-center">
            <h4 class="fancy">Iniciar sesión</h4>
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <!-- FORMULARIO LOGIN -->
        <div class="col-4"></div>
        <div class="col-4">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Login</h5>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label" style="text-align: left;">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" id="email" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label" style="text-align: left;">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" id="password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-custom ver w-100"><i class="fa-solid fa-check me-2"></i>Entrar</button>
                </form>
            </div>
        </div>
        <div class="col-4"></div>
        <!-- FIN FORMULARIO LOGIN -->

    </div>
    <!-- FIN CONTENIDO -->

    <!-- FOOTER -->
    <footer class="footer bg-light">
        <a href="https://www.afip.gob.ar/" target="_blank" rel="noopener noreferrer">
            <img id="fixed-image" src="img/chorro.png" alt="" data-bs-toggle="tooltip"
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
    <script src="js/tooltip.js"></script>
    <script src="js/fixedImage.js"></script>
    <!-- FIN JS -->
</body>

</html>