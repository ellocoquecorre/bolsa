<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

// Manejo de errores para responder siempre con JSON
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => "Error: $errstr en $errfile línea $errline"]);
    exit();
});

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['cliente_id'])) {
    $cliente_id = intval($data['cliente_id']);

    try {
        // Obtener nombre y apellido del cliente
        $stmt = $conexion->prepare("SELECT nombre, apellido FROM clientes WHERE cliente_id = :cliente_id");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->execute();
        $cliente = $stmt->fetch();

        if (!$cliente) {
            echo json_encode(['status' => 'error', 'message' => 'Cliente no encontrado']);
            exit;
        }

        $nombre = $cliente['nombre'];
        $apellido = $cliente['apellido'];

        // Tablas a eliminar
        $tables = [
            'acciones',
            'acciones_historial',
            'balance',
            'bonos',
            'bonos_historial',
            'cedear',
            'cedear_historial',
            'clientes',
            'fondos',
            'fondos_historial'
        ];

        // Iniciar transacción
        $conexion->beginTransaction();

        foreach ($tables as $table) {
            $stmt = $conexion->prepare("DELETE FROM $table WHERE cliente_id = :cliente_id");
            $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
            $stmt->execute();
        }

        $conexion->commit();

        echo json_encode([
            'status' => 'success',
            'nombre' => $nombre,
            'apellido' => $apellido
        ]);
    } catch (Exception $e) {
        $conexion->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Solicitud inválida']);
}
