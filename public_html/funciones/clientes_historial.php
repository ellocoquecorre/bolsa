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