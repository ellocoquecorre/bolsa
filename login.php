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
    <!-- CONTENIDO -->
    <div class="row justify-content-center mt-5">
        <div class="col-md-4">
            <!-- Imagen -->
            <div class="d-flex justify-content-center position-relative">
                <img src="img/inicio.png" alt="Inicio" class="img-fluid position-absolute custom-img">
            </div>
            <!-- Imagen -->
            <!-- Formulario Login -->
            <div class="custom-card">
                <h4 class="fancy">Iniciar sesión</h4>
                <hr class="mod mb-3">
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" id="email" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" id="password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-custom ver w-100"><i class="fa-solid fa-check me-2"></i>Entrar</button>
                </form>
            </div>
        </div>
    </div>
    </div>
    <!-- CONTENIDO -->

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- FIN JS -->
</body>

</html>