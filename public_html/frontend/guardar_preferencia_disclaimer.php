<?php
require_once '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cliente_id'])) {
    $cliente_id = (int)$_POST['cliente_id'];

    try {
        $stmt = $conexion->prepare("UPDATE clientes SET mostrar_disclaimer = 0 WHERE cliente_id = ?");
        $stmt->execute([$cliente_id]);
        http_response_code(200);
    } catch (PDOException $e) {
        error_log("Error al guardar preferencia disclaimer: " . $e->getMessage());
        http_response_code(500);
    }
} else {
    http_response_code(400);
}
