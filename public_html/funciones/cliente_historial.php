<?php

// Consolidada Acciones Pesos
$valor_compra_consolidado_acciones_pesos = 0;
$valor_venta_consolidado_acciones_pesos = 0;
foreach ($historial_acciones as $accion) {
    $total_compra_pesos_accion = $accion['cantidad'] * $accion['precio_compra'];
    $total_venta_pesos_accion = $accion['cantidad'] * $accion['precio_venta'];
    $valor_compra_consolidado_acciones_pesos += $total_compra_pesos_accion;
    $valor_venta_consolidado_acciones_pesos += $total_venta_pesos_accion;
}

$rendimiento_consolidado_acciones_pesos = $valor_venta_consolidado_acciones_pesos - $valor_compra_consolidado_acciones_pesos;
if ($valor_compra_consolidado_acciones_pesos != 0) {
    $rentabilidad_consolidado_acciones_pesos = ($rendimiento_consolidado_acciones_pesos / $valor_compra_consolidado_acciones_pesos) * 100;
} else {
    $rentabilidad_consolidado_acciones_pesos = 0;
}
// Fin Consolidada Acciones Pesos

// Consolidada Acciones Dolares
$valor_compra_consolidado_acciones_dolares = 0;
$valor_venta_consolidado_acciones_dolares = 0;
foreach ($historial_acciones as $accion) {
    $precio_compra_dolares_accion = $accion['precio_compra'] / $accion['ccl_compra'];
    $total_compra_dolares_accion = $accion['cantidad'] * $precio_compra_dolares_accion;
    $precio_venta_dolares_accion = $accion['precio_venta'] / $accion['ccl_venta'];
    $total_venta_dolares_accion = $accion['cantidad'] * $precio_venta_dolares_accion;
    $valor_compra_consolidado_acciones_dolares += $total_compra_dolares_accion;
    $valor_venta_consolidado_acciones_dolares += $total_venta_dolares_accion;
}
$rendimiento_consolidado_acciones_dolares = $valor_venta_consolidado_acciones_dolares - $valor_compra_consolidado_acciones_dolares;
if ($valor_compra_consolidado_acciones_dolares != 0) {
    $rentabilidad_consolidado_acciones_dolares = ($rendimiento_consolidado_acciones_dolares / $valor_compra_consolidado_acciones_dolares) * 100;
} else {
    $rentabilidad_consolidado_acciones_dolares = 0;
}
// Fin Consolidada Acciones Dolares

// Consolidada Cedear Pesos
$valor_compra_consolidado_cedear_pesos = 0;
$valor_venta_consolidado_cedear_pesos = 0;
foreach ($historial_cedear as $cedear) {
    $total_compra_pesos_cedear = $cedear['cantidad_cedear'] * $cedear['precio_compra_cedear'];
    $total_venta_pesos_cedear = $cedear['cantidad_cedear'] * $cedear['precio_venta_cedear'];
    $valor_compra_consolidado_cedear_pesos += $total_compra_pesos_cedear;
    $valor_venta_consolidado_cedear_pesos += $total_venta_pesos_cedear;
}

$rendimiento_consolidado_cedear_pesos = $valor_venta_consolidado_cedear_pesos - $valor_compra_consolidado_cedear_pesos;
if ($valor_compra_consolidado_cedear_pesos != 0) {
    $rentabilidad_consolidado_cedear_pesos = ($rendimiento_consolidado_cedear_pesos / $valor_compra_consolidado_cedear_pesos) * 100;
} else {
    $rentabilidad_consolidado_cedear_pesos = 0;
}
// Fin Consolidada Cedear Pesos

// Consolidada Cedear Dolares
$valor_compra_consolidado_cedear_dolares = 0;
$valor_venta_consolidado_cedear_dolares = 0;
foreach ($historial_cedear as $cedear) {
    $precio_compra_dolares_cedear = $cedear['precio_compra_cedear'] / $cedear['ccl_compra'];
    $total_compra_dolares_cedear = $cedear['cantidad_cedear'] * $precio_compra_dolares_cedear;
    $precio_venta_dolares_cedear = $cedear['precio_venta_cedear'] / $cedear['ccl_venta'];
    $total_venta_dolares_cedear = $cedear['cantidad_cedear'] * $precio_venta_dolares_cedear;
    $valor_compra_consolidado_cedear_dolares += $total_compra_dolares_cedear;
    $valor_venta_consolidado_cedear_dolares += $total_venta_dolares_cedear;
}
$rendimiento_consolidado_cedear_dolares = $valor_venta_consolidado_cedear_dolares - $valor_compra_consolidado_cedear_dolares;
if ($valor_compra_consolidado_cedear_dolares != 0) {
    $rentabilidad_consolidado_cedear_dolares = ($rendimiento_consolidado_cedear_dolares / $valor_compra_consolidado_cedear_dolares) * 100;
} else {
    $rentabilidad_consolidado_cedear_dolares = 0;
}
// Fin Consolidada Cedear Dolares

// Consolidada Bonos Pesos
$valor_compra_consolidado_bonos_pesos = 0;
$valor_venta_consolidado_bonos_pesos = 0;
foreach ($historial_bonos as $cedear) {
    $total_compra_pesos_bonos = $cedear['cantidad_bonos'] * $cedear['precio_compra_bonos'];
    $total_venta_pesos_bonos = $cedear['cantidad_bonos'] * $cedear['precio_venta_bonos'];
    $valor_compra_consolidado_bonos_pesos += $total_compra_pesos_bonos;
    $valor_venta_consolidado_bonos_pesos += $total_venta_pesos_bonos;
}

$rendimiento_consolidado_bonos_pesos = $valor_venta_consolidado_bonos_pesos - $valor_compra_consolidado_bonos_pesos;
if ($valor_compra_consolidado_bonos_pesos != 0) {
    $rentabilidad_consolidado_bonos_pesos = ($rendimiento_consolidado_bonos_pesos / $valor_compra_consolidado_bonos_pesos) * 100;
} else {
    $rentabilidad_consolidado_bonos_pesos = 0;
}
// Fin Consolidada Bonos Pesos

// Consolidada Bonos Dolares
$valor_compra_consolidado_bonos_dolares = 0;
$valor_venta_consolidado_bonos_dolares = 0;
foreach ($historial_bonos as $cedear) {
    $total_compra_dolares_bonos = $cedear['cantidad_bonos'] * $cedear['precio_compra_bonos'];
    $total_venta_dolares_bonos = $cedear['cantidad_bonos'] * $cedear['precio_venta_bonos'];
    $valor_compra_consolidado_bonos_dolares += $total_compra_dolares_bonos;
    $valor_venta_consolidado_bonos_dolares += $total_venta_dolares_bonos;
}

$rendimiento_consolidado_bonos_dolares = $valor_venta_consolidado_bonos_dolares - $valor_compra_consolidado_bonos_dolares;
if ($valor_compra_consolidado_bonos_dolares != 0) {
    $rentabilidad_consolidado_bonos_dolares = ($rendimiento_consolidado_bonos_dolares / $valor_compra_consolidado_bonos_dolares) * 100;
} else {
    $rentabilidad_consolidado_bonos_dolares = 0;
}
// Fin Consolidada Bonos Dolares

// Consolidada Fondos Pesos
$valor_compra_consolidado_fondos_pesos = 0;
$valor_venta_consolidado_fondos_pesos = 0;
foreach ($historial_fondos as $cedear) {
    $total_compra_pesos_fondos = $cedear['cantidad_fondos'] * $cedear['precio_compra_fondos'];
    $total_venta_pesos_fondos = $cedear['cantidad_fondos'] * $cedear['precio_venta_fondos'];
    $valor_compra_consolidado_fondos_pesos += $total_compra_pesos_fondos;
    $valor_venta_consolidado_fondos_pesos += $total_venta_pesos_fondos;
}

$rendimiento_consolidado_fondos_pesos = $valor_venta_consolidado_fondos_pesos - $valor_compra_consolidado_fondos_pesos;
if ($valor_compra_consolidado_fondos_pesos != 0) {
    $rentabilidad_consolidado_fondos_pesos = ($rendimiento_consolidado_fondos_pesos / $valor_compra_consolidado_fondos_pesos) * 100;
} else {
    $rentabilidad_consolidado_fondos_pesos = 0;
}
// Fin Consolidada Fondos Pesos

// Consolidada Fondos Dolares
$valor_compra_consolidado_fondos_dolares = 0;
$valor_venta_consolidado_fondos_dolares = 0;
foreach ($historial_fondos as $cedear) {
    $total_compra_dolares_fondos = $cedear['cantidad_fondos'] * $cedear['precio_compra_fondos'];
    $total_venta_dolares_fondos = $cedear['cantidad_fondos'] * $cedear['precio_venta_fondos'];
    $valor_compra_consolidado_fondos_dolares += $total_compra_dolares_fondos;
    $valor_venta_consolidado_fondos_dolares += $total_venta_dolares_fondos;
}

$rendimiento_consolidado_fondos_dolares = $valor_venta_consolidado_fondos_dolares - $valor_compra_consolidado_fondos_dolares;
if ($valor_compra_consolidado_fondos_dolares != 0) {
    $rentabilidad_consolidado_fondos_dolares = ($rendimiento_consolidado_fondos_dolares / $valor_compra_consolidado_fondos_dolares) * 100;
} else {
    $rentabilidad_consolidado_fondos_dolares = 0;
}
// Fin Consolidada Fondos Dolares