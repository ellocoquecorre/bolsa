<?php
// Datos de conexión - ajustá estos valores según tu entorno
$host = 'localhost';
$dbname = 'goodfellas';
$usuario = 'root';
$contrasena = 'incitato'; // reemplazá por la tuya

try {
    // Conexión usando PDO
    $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $usuario, $contrasena);

    // Modo de errores como excepciones
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Modo de obtención de datos por defecto como array asociativo
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Opcional: activar emulación de consultas preparadas (desactivado para mayor seguridad)
    $conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    // Manejo de errores más seguro: no mostrar detalles en producción
    error_log("Error de conexión: " . $e->getMessage());
    die("No se pudo conectar a la base de datos.");
}
