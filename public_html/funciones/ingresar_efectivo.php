<?php
// Incluir archivo de configuración
require_once '../../config/config.php';
require_once '../funciones/formato_dinero.php';

// Obtener el id del cliente desde la URL
$cliente_id = isset($_GET['cliente_id']) ? $_GET['cliente_id'] : 1;

// Obtener los datos del cliente
$sql = "SELECT nombre, apellido FROM clientes WHERE cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$stmt->bind_result($nombre, $apellido);
$stmt->fetch();
$stmt->close();

// Obtener el saldo en pesos del cliente
$sql_saldo = "SELECT efectivo FROM balance WHERE cliente_id = ?";
$stmt_saldo = $conn->prepare($sql_saldo);
$stmt_saldo->bind_param("i", $cliente_id);
$stmt_saldo->execute();
$stmt_saldo->bind_result($saldo_en_pesos);
$stmt_saldo->fetch();
$stmt_saldo->close();
$saldo_en_pesos_formateado = formatear_dinero($saldo_en_pesos);

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el monto ingresado
    $monto_ingresado = floatval(str_replace(',', '.', $_POST['monto']));
    // Sumar el monto ingresado al saldo actual
    $nuevo_saldo = $saldo_en_pesos + $monto_ingresado;

    // Actualizar el saldo en la base de datos
    $sql_update = "UPDATE balance SET efectivo = ? WHERE cliente_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("di", $nuevo_saldo, $cliente_id);

    if ($stmt_update->execute()) {
        // Mostrar mensaje de éxito y redirigir
        echo "<script>
            alert('Operación realizada con éxito');
            window.location.href = '../backend/lista_clientes.php';
        </script>";
    } else {
        echo "<script>alert('Error al realizar la operación');</script>";
    }
    $stmt_update->close();
}

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
    <!-- PRELOADER -->
    <div id="preloader">
        <img src="../img/preloader.gif" alt="Preloader" class="main-img">
        <img src="../img/frases.gif" alt="Frases" class="frase-img">
    </div>
    <!-- FIN PRELOADER -->

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
            <h4 class="fancy"><?php echo $nombre_y_apellido; ?></h4>
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <!-- INGRESAR EFECTIVO -->
        <div class="col-4"></div>
        <div class="col-4 text-center">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Ingresar Efectivo</h5>
                <form id="compra_acciones" method="POST" action="">
                    <input type="hidden" name="cliente_id" value="<?php echo $cliente_id; ?>">
                    <!-- Saldo -->
                    <div class="row mb-3 align-items-center">
                        <label for="saldo" class="col-sm-2 col-form-label">Saldo</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-chart-line"></i></span>
                                <input type="text" class="form-control" id="saldo" name="saldo"
                                    value="$ <?php echo $saldo_en_pesos_formateado; ?>" readonly disabled>
                            </div>
                        </div>
                    </div>
                    <!-- Monto -->
                    <div class="row mb-3 align-items-center">
                        <label for="monto" class="col-sm-2 col-form-label">Monto</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-dollar-sign"></i></span>
                                <input type="text" step="0.01" class="form-control" id="monto" name="monto"
                                    placeholder="0,00" required>
                            </div>
                        </div>
                    </div>
                    <hr class="mod mb-3">
                    <!-- Botones -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-custom ver"><i class="fa-solid fa-check me-2"></i>Aceptar</button>
                        <button type="button" class="btn btn-custom eliminar"
                            onclick="window.location.href='../backend/lista_clientes.php'">
                            <i class="fa-solid fa-times me-2"></i>Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-4"></div>
        <!-- FIN INGRESAR EFECTIVO -->

    </div>
    <!-- FIN CONTENIDO -->

    <!-- FOOTER -->
    <footer class="footer bg-light">
        <img id="fixed-image" src="../img/chorro.png" alt="Imagen Fija" />
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
    <!-- FIN JS -->
</body>

</html>