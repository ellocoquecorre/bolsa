<?php
require_once '../../config/config.php'; // Incluir archivo de configuración

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = $_POST['cliente_id'];
    $ticker = $_POST['ticker'];

    // Obtener la cantidad y precio del ticker a eliminar
    $sql = "SELECT cantidad, precio FROM acciones WHERE cliente_id = ? AND ticker = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $cliente_id, $ticker);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_valor = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $total_valor += $row['cantidad'] * $row['precio'];
        }
    }

    // Eliminar registros del ticker para el cliente_id
    $sql = "DELETE FROM acciones WHERE cliente_id = ? AND ticker = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $cliente_id, $ticker);
    $stmt->execute();

    // Actualizar el balance del cliente
    $sql = "UPDATE balance SET efectivo = efectivo + ? WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $total_valor, $cliente_id);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
