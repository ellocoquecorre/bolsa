<?php
// Incluir archivo de configuración
require_once '../../config/config.php';
require_once 'dolar_api.php'; // Requiere el archivo donde está la función calcular_saldo_dolares

function retirar_efectivo($cliente_id, $monto)
{
    global $conn, $contadoconliqui_compra, $contadoconliqui_venta;

    // Obtener el saldo actual
    $sql = "SELECT efectivo FROM balance WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $balance = $result->fetch_assoc();

    if ($balance) {
        if ($balance['efectivo'] >= $monto) {
            $nuevo_saldo = $balance['efectivo'] - $monto;

            // Actualizar el saldo
            $sql_update = "UPDATE balance SET efectivo = ? WHERE usuario_id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("di", $nuevo_saldo, $cliente_id);
            $stmt_update->execute();

            // Calcular el nuevo saldo en dólares
            $nuevo_saldo_dolares = calcular_saldo_dolares($nuevo_saldo, $contadoconliqui_compra, $contadoconliqui_venta);

            return [
                'success' => true,
                'nuevo_saldo' => $nuevo_saldo,
                'nuevo_saldo_dolares' => $nuevo_saldo_dolares
            ];
        } else {
            return ['success' => false, 'message' => 'Saldo insuficiente']; // Saldo insuficiente
        }
    } else {
        return ['success' => false, 'message' => 'Error al obtener el balance']; // Error al obtener el balance
    }
}

// Verificar si la petición es AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = isset($_POST['cliente_id']) ? (int)$_POST['cliente_id'] : 0;
    $monto = isset($_POST['monto']) ? (float)$_POST['monto'] : 0.00;

    if ($cliente_id > 0 && $monto > 0) {
        $resultado = retirar_efectivo($cliente_id, $monto);
        if ($resultado['success']) {
            echo json_encode([
                'success' => true,
                'nuevo_saldo' => number_format($resultado['nuevo_saldo'], 2, ',', '.'),
                'nuevo_saldo_dolares' => number_format($resultado['nuevo_saldo_dolares'], 2, ',', '.')
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => $resultado['message']]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    }
}
