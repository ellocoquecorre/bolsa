<?php
// eliminar_acciones.php
include('../../config/config.php'); // Asegúrate de incluir la conexión a la base de datos

// Obtener los datos enviados por AJAX
$data = json_decode(file_get_contents('php://input'), true);
$ticker = $data['ticker'];
$cliente_id = $data['cliente_id'];

// Preparar la consulta para obtener los valores de la acción antes de eliminarla
$sql = "SELECT cantidad, precio FROM acciones WHERE cliente_id = ? AND ticker = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $cliente_id, $ticker);
$stmt->execute();
$stmt->bind_result($cantidad, $precio);
$total_valor_eliminado = 0;

while ($stmt->fetch()) {
    // Calculamos el valor de la acción eliminada
    $total_valor_eliminado += $cantidad * $precio;
}
$stmt->close();

// Si no se encontró la acción, detener el proceso
if ($total_valor_eliminado == 0) {
    echo json_encode(['success' => false, 'message' => 'No se encontró la acción']);
    exit();
}

// Ahora, sumamos el valor de las acciones eliminadas al balance del cliente
$sql_balance = "UPDATE balance SET efectivo = efectivo + ? WHERE cliente_id = ?";
$stmt_balance = $conn->prepare($sql_balance);
$stmt_balance->bind_param("di", $total_valor_eliminado, $cliente_id);
$stmt_balance->execute();
$stmt_balance->close();

// Eliminar las acciones de la tabla
$sql_delete = "DELETE FROM acciones WHERE cliente_id = ? AND ticker = ?";
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param("is", $cliente_id, $ticker);
$stmt_delete->execute();
$stmt_delete->close();

// Devolver una respuesta exitosa en formato JSON
echo json_encode(['success' => true, 'message' => 'Acción eliminada correctamente']);
