<?php
// Incluir archivo de configuración
require_once '../../config/config.php';

// Obtener el ID del cliente de la URL
$cliente_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($cliente_id > 0) {
    // Realizar la consulta a la base de datos para obtener los datos del cliente
    $sql = "SELECT nombre, apellido FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();

    if (!$cliente) {
        echo "Cliente no encontrado.";
        exit;
    }

    // Consultar el saldo en efectivo del cliente
    $sql_balance = "SELECT efectivo FROM balance WHERE cliente_id = ?";
    $stmt_balance = $conn->prepare($sql_balance);
    $stmt_balance->bind_param("i", $cliente_id);
    $stmt_balance->execute();
    $result_balance = $stmt_balance->get_result();
    $balance = $result_balance->fetch_assoc();

    if (!$balance) {
        $balance['efectivo'] = 0.00; // Si no hay registro de balance, el saldo es 0.00
    }
} else {
    echo "ID de cliente inválido.";
    exit;
}
