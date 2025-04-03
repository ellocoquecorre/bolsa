<?php
require_once '../../config/config.php';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Error de conexión: ' . $conn->connect_error]));
}

header('Content-Type: application/json');

// Set error handler to always return JSON
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => "Error: $errstr in $errfile on line $errline"]);
    exit();
});

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['cliente_id'])) {
    $cliente_id = intval($data['cliente_id']);

    // Obtener nombre y apellido del cliente
    $stmt = $conn->prepare("SELECT nombre, apellido FROM clientes WHERE cliente_id = ?");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $stmt->bind_result($nombre, $apellido);
    $stmt->fetch();
    $stmt->close();

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

    $conn->begin_transaction();

    try {
        foreach ($tables as $table) {
            $stmt = $conn->prepare("DELETE FROM $table WHERE cliente_id = ?");
            if (!$stmt) {
                throw new Exception('Failed to prepare statement: ' . $conn->error);
            }
            $stmt->bind_param("i", $cliente_id);
            $stmt->execute();
            $stmt->close();
        }

        $conn->commit();
        echo json_encode(['status' => 'success', 'nombre' => $nombre, 'apellido' => $apellido]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
