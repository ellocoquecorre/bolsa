<?php
// Verificar si la sesión ya está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destruir la sesión
session_destroy();

// Redirigir al login
header('Location: login.php');
exit;
