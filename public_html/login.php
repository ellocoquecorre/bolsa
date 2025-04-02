<?php
// 1. Carga de configuración
require_once __DIR__ . '/../config/config.php';

// 2. Manejo de sesión
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// 3. Procesamiento del formulario
$error = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // 4. Conexión a DB usando las variables de config.php
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        $error = "Error de conexión. Intente nuevamente más tarde.";
    } else {
        try {
            // Verificación para admin
            $stmt = $conn->prepare("SELECT id, password FROM admin WHERE email = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    if (password_verify($password, $row['password'])) {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['email'] = $email;
                        $_SESSION['user_id'] = $row['id'];
                        $_SESSION['user_type'] = 'admin';

                        // Guardar la conexión en sesión para usar en lista_clientes.php
                        $_SESSION['db_connection'] = [
                            'host' => $db_host,
                            'user' => $db_user,
                            'pass' => $db_pass,
                            'name' => $db_name
                        ];

                        $stmt->close();
                        $conn->close();
                        header("Location: backend/lista_clientes.php");
                        exit;
                    }
                }
                $stmt->close();
            }

            // Verificación para clientes
            $stmt = $conn->prepare("SELECT cliente_id, password FROM clientes WHERE email = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    if (password_verify($password, $row['password'])) {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['email'] = $email;
                        $_SESSION['cliente_id'] = $row['cliente_id'];
                        $_SESSION['user_type'] = 'cliente';

                        $stmt->close();
                        $conn->close();
                        header("Location: frontend/cliente.php?cliente_id=" . $row['cliente_id']);
                        exit;
                    }
                }
                $stmt->close();
            }

            $error = "El correo electrónico o la contraseña son incorrectos.";
        } catch (Exception $e) {
            $error = "Ocurrió un error. Intente nuevamente.";
        }
        $conn->close();
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
                            <input type="email" name="email" class="form-control" id="email" required autofocus>
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