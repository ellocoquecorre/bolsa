<?php
// Incluir archivo de configuración
require_once '../../config/config.php';
require_once '../funciones/formato_dinero.php';
require_once '../funciones/cliente_funciones.php';

// Inicializar el mensaje de error
$error_msg = '';

// Obtener el id del cliente desde la URL
$cliente_id = isset($_GET['cliente_id']) ? $_GET['cliente_id'] : 1;
$ticker = isset($_GET['ticker']) ? $_GET['ticker'] : '';

// Obtener los datos del cliente
$sql = "SELECT nombre, apellido FROM clientes WHERE cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$stmt->bind_result($nombre, $apellido);
$stmt->fetch();
$stmt->close();

// Obtener los datos de la compra de acciones
$sql_acciones = "SELECT cantidad, precio, fecha, ccl_compra FROM acciones WHERE cliente_id = ? AND ticker = ?";
$stmt_acciones = $conn->prepare($sql_acciones);
$stmt_acciones->bind_param("is", $cliente_id, $ticker);
$stmt_acciones->execute();
$stmt_acciones->bind_result($cantidad, $precio, $fecha, $ccl_compra);
$stmt_acciones->fetch();
$stmt_acciones->close();

// Renderizar los datos obtenidos
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
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
            <h4 class="fancy"><?php echo $nombre_y_apellido; ?></h4>
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <!-- EDITAR ACCIONES -->
        <div class="col-3"></div>
        <div class="col-6 text-center">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Editar los datos de <?php echo htmlspecialchars($ticker); ?></h5>
                <form method="POST" action="">

                    <!-- Cantidad -->
                    <div class="row mb-3 align-items-center">
                        <label for="cantidad" class="col-sm-2 col-form-label">Cantidad</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-hashtag"></i></span>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="<?php echo htmlspecialchars($cantidad); ?>" value="" autofocus required>
                            </div>
                        </div>
                    </div>
                    <!-- Fin Cantidad -->

                    <!-- Precio -->
                    <div class="row mb-3 align-items-center">
                        <label for="precio" class="col-sm-2 col-form-label">Precio</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                <input type="text" step="0.01" class="form-control" id="precio" name="precio" placeholder="<?php echo htmlspecialchars($precio); ?>" value="" required>
                            </div>
                        </div>
                    </div>
                    <!-- Fin Precio -->

                    <!-- CCL -->
                    <div class="row mb-3 align-items-center">
                        <label for="ccl_compra" class="col-sm-2 col-form-label">Dolar CCL</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                <input type="text" step="0.01" class="form-control" id="ccl_compra" name="ccl_compra" placeholder="<?php echo htmlspecialchars($ccl_compra); ?>" value="" required>
                            </div>
                        </div>
                    </div>
                    <!-- Fin CCL -->

                    <!-- Fecha -->
                    <div class="row mb-3 align-items-center">
                        <label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-alt"></i></span>
                                <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>" required>
                            </div>
                        </div>
                    </div>
                    <!-- Fin Fecha -->

                    <hr class="mod mb-3">

                    <!-- Botones -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-custom ver"><i class="fa-solid fa-check me-2"></i>Aceptar</button>
                        <a href="../backend/cliente.php?cliente_id=<?php echo $cliente_id; ?>#acciones"
                            class="btn btn-custom eliminar"><i class="fa-solid fa-times me-2"></i>Cancelar</a>
                    </div>
                    <!-- Fin Botones -->

                </form>
            </div>
        </div>
        <div class="col-3"></div>
        <!-- FIN EDITAR ACCIONES -->

    </div>
    <!-- FIN CONTENIDO -->

    <!-- FOOTER -->
    <img id="fixed-image" src="../img/chorro.png" alt="Imagen Fija" />
    <footer class="footer bg-light">
        <div class="container">
            <span class="text-muted">© GoodFellas Inc.</span>
        </div>
    </footer>
    <!-- FIN FOOTER -->

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- FIN JS -->
</body>

</html>