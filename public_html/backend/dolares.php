<?php
// 1. Verificación de sesión segura
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}

// 2. Obtener datos del dólar
require_once '../funciones/dolar_cronista.php';
require_once '../funciones/formato_dinero.php';

/**
 * Calcula el promedio entre los valores de compra y venta
 * @param mixed $compra Valor de compra
 * @param mixed $venta Valor de venta
 * @return mixed Promedio o "-" si hay valores inválidos
 */
function calcularPromedio($compra, $venta)
{
    if ($compra === "-" || $venta === "-" || !is_numeric($compra) || !is_numeric($venta)) {
        return "-";
    }
    return ($compra + $venta) / 2;
}

// 3. Organizar datos en estructura clara
$cotizaciones = [
    'Oficial' => [
        'compra' => $oficial_compra,
        'venta' => $oficial_venta
    ],
    'Blue' => [
        'compra' => $blue_compra,
        'venta' => $blue_venta
    ],
    'MEP' => [
        'compra' => $bolsa_compra,
        'venta' => $bolsa_venta
    ],
    'Tarjeta' => [
        'compra' => $tarjeta_compra,
        'venta' => $tarjeta_venta
    ],
    'Mayorista' => [
        'compra' => $mayorista_compra,
        'venta' => $mayorista_venta
    ],
    'Contado con Liqui' => [
        'compra' => $contadoconliqui_compra,
        'venta' => $contadoconliqui_venta
    ]
];
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="lista_clientes.php"><i class="fa-solid fa-users me-2"></i>Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="alta_clientes.php"><i class="fa-solid fa-user-plus me-2"></i>Alta Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="dolares.php"><i class="fa-solid fa-dollar-sign me-2"></i>Dólares</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php"><i class="fa-solid fa-power-off me-2"></i>Salir</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- FIN NAVBAR -->

    <!-- CONTENIDO -->
    <div class="row mx-2 mt-navbar">

        <!-- TITULO -->
        <div class="col-12 text-center">
            <h4 class="fancy">Cotización de Dólares</h4>
        </div>
        <!-- FIN TITULO -->

        <hr class="mod">

        <!-- TABLA DÓLARES -->
        <div class="col-3"></div>
        <div class="col-md-12 col-lg-6 text-center">
            <div class="container-fluid my-4 efectivo">
                <h5 class="me-2 cartera titulo-botones mb-4">Tipos de cambio</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>Tipo</th>
                                <th>Compra</th>
                                <th>Venta</th>
                                <th>Promedio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cotizaciones as $tipo => $cotizacion): ?>
                                <tr>
                                    <td class="fw-bold text-left"><?= htmlspecialchars($tipo) ?></td>
                                    <td class="text-right"><?= ($cotizacion['compra'] !== "-") ? '$ ' . formatear_dinero($cotizacion['compra'], 2) : '-' ?></td>
                                    <td class="text-right"><?= ($cotizacion['venta'] !== "-") ? '$ ' . formatear_dinero($cotizacion['venta'], 2) : '-' ?></td>
                                    <td class="text-right">
                                        <?php
                                        $promedio = calcularPromedio($cotizacion['compra'], $cotizacion['venta']);
                                        echo ($promedio !== "-") ? '$ ' . formatear_dinero($promedio, 2) : '-';
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-3"></div>
        <!-- FIN TABLA DÓLARES -->

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