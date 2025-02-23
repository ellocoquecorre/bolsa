<?php
require_once '../../config/config.php';
require_once 'dolar_api.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = isset($_POST['cliente_id']) ? (int)$_POST['cliente_id'] : 0;
    $monto = isset($_POST['monto']) ? (float)str_replace(',', '.', str_replace('.', '', $_POST['monto'])) : 0;

    if ($cliente_id > 0 && $monto > 0) {
        // Actualizar el saldo en efectivo del cliente
        $sql = "UPDATE balance SET efectivo = efectivo + ? WHERE cliente_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $monto, $cliente_id);
        if ($stmt->execute()) {
            // Obtener el nuevo saldo
            $sql_balance = "SELECT efectivo FROM balance WHERE cliente_id = ?";
            $stmt_balance = $conn->prepare($sql_balance);
            $stmt_balance->bind_param("i", $cliente_id);
            $stmt_balance->execute();
            $result_balance = $stmt_balance->get_result();
            $balance = $result_balance->fetch_assoc();

            // Calcular el saldo en dólares
            $saldo_dolares = calcular_saldo_dolares($balance['efectivo'], $contadoconliqui_compra, $contadoconliqui_venta);

            echo json_encode([
                'success' => true,
                'nuevo_saldo_pesos' => number_format($balance['efectivo'], 2, ',', '.'),
                'nuevo_saldo_dolares' => is_numeric($saldo_dolares) ? number_format($saldo_dolares, 2, ',', '.') : $saldo_dolares
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
$conn->close();
