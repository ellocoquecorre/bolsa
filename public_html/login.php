<?php
// 1. Cargar configuración con conexión PDO
require_once __DIR__ . '/../config/config.php';

// 2. Iniciar sesión
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// 3. Manejo de errores
$error = null;

// 4. Procesamiento del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    try {
        // Verificar si es admin
        $stmt = $conexion->prepare("SELECT id, password FROM admin WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);

        if ($row = $stmt->fetch()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_type'] = 'admin';

                header("Location: backend/lista_clientes.php");
                exit;
            }
        }

        // Verificar si es cliente
        $stmt = $conexion->prepare("SELECT cliente_id, password FROM clientes WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);

        if ($row = $stmt->fetch()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['cliente_id'] = $row['cliente_id'];
                $_SESSION['user_type'] = 'cliente';

                header("Location: frontend/cliente.php?cliente_id=" . $row['cliente_id']);
                exit;
            }
        }

        $error = "El correo electrónico o la contraseña son incorrectos.";
    } catch (Exception $e) {
        error_log("Error de login: " . $e->getMessage());
        $error = "Ocurrió un error. Intente nuevamente.";
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
            <h4 class="fancy">GoodFellas</h4>
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <div class="col-12 text-center">
            <p class="cartera">Tus finanzas en manos expertas.<br>
                O casi expertas.<br>
                Mas o menos...
            </p>
        </div>

        <!-- FORMULARIO LOGIN -->
        <div class="col-4"></div>
        <div class="col-md-12 col-lg-4 text-center">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Login</h5>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <!-- Email -->
                    <div class="row mb-3 align-items-center">
                        <label for="saldo" class="col-sm-3 col-form-label text-start text-lg-end text-start text-lg-end">Email</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" id="email" required autofocus>
                            </div>
                        </div>
                    </div>
                    <!-- Contraseña -->
                    <div class="row mb-3 align-items-center">
                        <label for="monto" class="col-sm-3 col-form-label text-start text-lg-end text-start text-lg-end">Contraseña</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" id="password" required>
                            </div>
                        </div>
                    </div>
                    <hr class="mod mb-3">
                    <!-- Botones -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-custom ver"><i class="fa-solid fa-check me-2"></i>Entrar</button>
                    </div>
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