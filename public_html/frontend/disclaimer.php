<?php
session_start();
require_once '../config.php'; // o como cargues tu conexión

$data = json_decode(file_get_contents("php://input"), true);
$mostrar = isset($data['mostrar_disclaimer']) ? (int)$data['mostrar_disclaimer'] : 1;

$cliente_id = $_SESSION['cliente_id'] ?? null;

if ($cliente_id) {
    $stmt = $pdo->prepare("UPDATE clientes SET mostrar_disclaimer = ? WHERE cliente_id = ?");
    $success = $stmt->execute([$mostrar, $cliente_id]);

    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'error' => 'Usuario no logueado']);
}
