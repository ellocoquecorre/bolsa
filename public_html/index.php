<?php
// Iniciar sesión
session_start();

// Incluir el archivo de configuración
require_once '../config/config.php';

// Función para verificar las credenciales en la base de datos
function verificarCredenciales($conexion, $mail, $password, $tabla)
{
    $stmt = $conexion->prepare("SELECT * FROM $tabla WHERE mail = ? AND password = ?");
    $stmt->bind_param("ss", $mail, $password);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->num_rows > 0;
}

// Verificar si el usuario ya está logueado
if (isset($_SESSION['mail']) && isset($_SESSION['password'])) {
    $mail = $_SESSION['mail'];
    $password = $_SESSION['password'];

    // Crear conexión a la base de datos
    $conexion = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Verificar en la tabla admin
    if (verificarCredenciales($conexion, $mail, $password, 'admin')) {
        header('Location: backend/cliente.php');
        exit;
    }

    // Verificar en la tabla clientes
    if (verificarCredenciales($conexion, $mail, $password, 'clientes')) {
        header('Location: frontend/cliente.php');
        exit;
    }

    // Cerrar la conexión
    $conexion->close();
}

// Si no está logueado, redirigir a login.php
header('Location: login.php');
exit;
