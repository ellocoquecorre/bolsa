<?php
// CONFIG
require_once '../../config/config.php';

// CLIENTE_ID
$cliente_id = isset($_POST['cliente_id']) ? $_POST['cliente_id'] : (isset($_GET['cliente_id']) ? $_GET['cliente_id'] : 1);

// DATOS DEL CLIENTE
$sql = "SELECT nombre, apellido FROM clientes WHERE cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$stmt->bind_result($nombre, $apellido);
$stmt->fetch();
$stmt->close();
// FIN DATOS DEL CLIENTE

// BALANCE DEL CLIENTE
$sql = "SELECT efectivo FROM balance WHERE cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$stmt->bind_result($saldo_efectivo);
$stmt->fetch();
$stmt->close();
// FIN BALANCE DEL CLIENTE
