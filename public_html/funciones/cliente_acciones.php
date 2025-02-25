<?php

// DATOS DEL CLIENTE
$sql = "SELECT nombre, apellido FROM clientes WHERE cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$stmt->bind_result($nombre, $apellido);
$stmt->fetch();
$stmt->close();

// BALANCE DEL CLIENTE
$saldo_efectivo = $balance['efectivo'];
$saldo_dolares = calcular_saldo_dolares($saldo_efectivo, $contadoconliqui_compra, $contadoconliqui_venta);
