<?php
// Incluir archivo de configuración
require_once '../../config/config.php';

// Definir la función calcular_saldo_dolares si no está definida
if (!function_exists('calcular_saldo_dolares')) {
    function calcular_saldo_dolares($saldo_efectivo, $contadoconliqui_compra, $contadoconliqui_venta)
    {
        // Implementación de la función
        return $saldo_efectivo / (($contadoconliqui_compra + $contadoconliqui_venta) / 2);
    }
}

// Inicializar variables necesarias
$cliente_id = 1; // Suponemos que el cliente_id es 1
$contadoconliqui_compra = 100; // Ejemplo de valor
$contadoconliqui_venta = 95; // Ejemplo de valor

// DATOS DEL CLIENTE
$sql = "SELECT nombre, apellido FROM clientes WHERE cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$stmt->bind_result($nombre, $apellido);
$stmt->fetch();
$stmt->close();

// BALANCE DEL CLIENTE
$sql = "SELECT efectivo FROM balance WHERE cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$stmt->bind_result($saldo_efectivo);
$stmt->fetch();
$stmt->close();

$saldo_dolares = calcular_saldo_dolares($saldo_efectivo, $contadoconliqui_compra, $contadoconliqui_venta);

// COMPRA DE ACCIONES
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $ticker = $_POST['ticker'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $fecha = $_POST['fecha'];

    // Calcular el total de la compra
    $total_compra = $cantidad * $precio;

    // Verificar si el saldo es suficiente
    if ($total_compra <= $saldo_efectivo) {
        // Insertar la compra en la tabla "acciones"
        $sql = "INSERT INTO acciones (cliente_id, ticker, cantidad, precio, fecha) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isids", $cliente_id, $ticker, $cantidad, $precio, $fecha);
        if ($stmt->execute()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            header("Location: ../backend/cliente.php?cliente_id=$cliente_id#acciones");
            exit();
        } else {
            $error_msg = "Error al guardar la compra. Por favor, intente nuevamente.";
        }
        $stmt->close();
    } else {
        // Saldo insuficiente
        $error_msg = "Saldo insuficiente";
    }
}
