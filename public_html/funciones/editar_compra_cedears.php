<?php
// Incluir archivo de configuración
require_once '../../config/config.php';
require_once '../funciones/formato_dinero.php';
require_once '../funciones/cliente_funciones.php';

// Inicializar el mensaje de error
$error_msg = '';

// Obtener el id del cliente desde la URL
$cliente_id = isset($_GET['cliente_id']) ? intval($_GET['cliente_id']) : 1;
$ticker = isset($_GET['ticker']) ? $_GET['ticker'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores del formulario
    $cantidad_nueva = isset($_POST['cantidad']) ? floatval($_POST['cantidad']) : 0;
    $precio_nuevo = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;
    $ccl_compra_nuevo = isset($_POST['ccl_compra']) ? $_POST['ccl_compra'] : 0;
    $fecha_nueva = isset($_POST['fecha']) ? date('Y-m-d', strtotime($_POST['fecha'])) : date('Y-m-d');

    // Formatear el valor del campo ccl_compra
    $ccl_compra_nuevo_formateado = str_replace('.', '', $ccl_compra_nuevo); // Eliminar puntos
    $ccl_compra_nuevo_formateado = str_replace(',', '.', $ccl_compra_nuevo_formateado); // Reemplazar comas por puntos

    // Obtener el saldo en pesos del cliente
    $stmt_saldo = $conexion->prepare("SELECT efectivo FROM balance WHERE cliente_id = ?");
    $stmt_saldo->execute([$cliente_id]);
    $saldo_en_pesos = $stmt_saldo->fetchColumn();

    // Obtener los datos del cedear actual
    $stmt_cedear = $conexion->prepare("SELECT cantidad_cedear, precio_cedear, ccl_compra_cedear FROM cedear WHERE cliente_id = ? AND ticker_cedear = ?");
    $stmt_cedear->execute([$cliente_id, $ticker]);
    $cedear = $stmt_cedear->fetch();

    if (!$cedear) {
        $error_msg = "No se encontró el CEDEAR para este cliente.";
    } else {
        $cantidad_actual = $cedear['cantidad_cedear'];
        $precio_actual = $cedear['precio_cedear'];
        $ccl_compra_actual = $cedear['ccl_compra_cedear'];

        // Calcular el nuevo costo total
        $costo_total_nuevo = $cantidad_nueva * $precio_nuevo;

        // Verificar si hay saldo suficiente
        if ($costo_total_nuevo > $saldo_en_pesos) {
            $error_msg = "Saldo insuficiente";
        } else {
            // Actualizar los datos del CEDEAR
            $stmt_update_cedear = $conexion->prepare(
                "UPDATE cedear 
                 SET cantidad_cedear = ?, precio_cedear = ?, ccl_compra_cedear = ?, fecha_cedear = ? 
                 WHERE cliente_id = ? AND ticker_cedear = ?"
            );
            $stmt_update_cedear->execute([
                $cantidad_nueva,
                $precio_nuevo,
                $ccl_compra_nuevo_formateado,
                $fecha_nueva,
                $cliente_id,
                $ticker
            ]);

            // Calcular la diferencia de costo
            $diferencia_costo = ($cantidad_nueva * $precio_nuevo) - ($cantidad_actual * $precio_actual);

            if ($diferencia_costo < 0) {
                $nuevo_saldo_en_pesos = $saldo_en_pesos + abs($diferencia_costo);
            } else {
                $nuevo_saldo_en_pesos = $saldo_en_pesos - abs($diferencia_costo);
            }

            // Actualizar el saldo del cliente
            $stmt_update_balance = $conexion->prepare("UPDATE balance SET efectivo = ? WHERE cliente_id = ?");
            $stmt_update_balance->execute([$nuevo_saldo_en_pesos, $cliente_id]);

            // Redireccionar
            header("Location: ../backend/cliente.php?cliente_id=$cliente_id#cedears");
            exit();
        }
    }
}

// Obtener los datos del cliente
$stmt_cliente = $conexion->prepare("SELECT nombre, apellido FROM clientes WHERE cliente_id = ?");
$stmt_cliente->execute([$cliente_id]);
$cliente = $stmt_cliente->fetch();
$nombre_y_apellido = $cliente ? htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) : 'Cliente desconocido';

// Obtener los datos del CEDEAR para el formulario
$stmt_cedear_form = $conexion->prepare(
    "SELECT cantidad_cedear, precio_cedear, fecha_cedear, ccl_compra_cedear 
     FROM cedear 
     WHERE cliente_id = ? AND ticker_cedear = ?"
);
$stmt_cedear_form->execute([$cliente_id, $ticker]);
$cedear_form = $stmt_cedear_form->fetch();

$cantidad = $cedear_form['cantidad_cedear'] ?? 0;
$precio = $cedear_form['precio_cedear'] ?? 0;
$fecha = $cedear_form['fecha_cedear'] ?? date('Y-m-d');
$ccl_compra = $cedear_form['ccl_compra_cedear'] ?? 0;
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
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
                        <a class="nav-link" href="dolares.php"><i class="fa-solid fa-dollar-sign me-2"></i>Dólares
                        </a>
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
            <h4 class="fancy"><?php echo $nombre_y_apellido; ?></h4>
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <!-- EDITAR CEDEAR -->
        <div class="col-3"></div>
        <div class="col-md-12 col-lg-6 text-center">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Editar los datos de <?php echo htmlspecialchars($ticker); ?></h5>
                <?php if ($error_msg): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_msg; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="">
                    <!-- Cantidad -->
                    <div class="row mb-3 align-items-center">
                        <label for="cantidad" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Cantidad</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-hashtag"></i></span>
                                <input type="number" class="form-control" id="cantidad" name="cantidad"
                                    value="<?php echo htmlspecialchars($cantidad); ?>" autofocus required>
                            </div>
                        </div>
                    </div>
                    <!-- Fin Cantidad -->

                    <!-- Precio -->
                    <div class="row mb-3 align-items-center">
                        <label for="precio" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Precio</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                <input type="text" step="0.01" class="form-control" id="precio" name="precio"
                                    value="<?php echo htmlspecialchars($precio); ?>" required>
                            </div>
                        </div>
                    </div>
                    <!-- Fin Precio -->

                    <!-- CCL -->
                    <div class="row mb-3 align-items-center">
                        <label for="ccl_compra" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Dólar CCL</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                <input type="text" step="0.01" class="form-control" id="ccl_compra" name="ccl_compra"
                                    value="<?php echo htmlspecialchars($ccl_compra); ?>" required>
                            </div>
                        </div>
                    </div>
                    <!-- Fin CCL -->

                    <!-- Fecha -->
                    <div class="row mb-3 align-items-center">
                        <label for="fecha" class="col-sm-2 col-form-label text-start text-lg-end text-start text-lg-end">Fecha</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-alt"></i></span>
                                <input type="date" class="form-control" id="fecha" name="fecha"
                                    value="<?php echo htmlspecialchars($fecha); ?>" required>
                            </div>
                        </div>
                    </div>
                    <!-- Fin Fecha -->

                    <hr class="mod mb-3">

                    <!-- Botones -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-custom ver"><i class="fa-solid fa-check me-2"></i>Aceptar</button>
                        <a href="../backend/cliente.php?cliente_id=<?php echo $cliente_id; ?>#cedears"
                            class="btn btn-custom eliminar"><i class="fa-solid fa-times me-2"></i>Cancelar</a>
                    </div>
                    <!-- Fin Botones -->

                </form>
            </div>
        </div>
        <div class="col-3"></div>
        <!-- FIN EDITAR CEDEAR -->

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
    <script src="../js/easter_egg.js"></script>
    <!-- FIN JS -->
</body>

</html>