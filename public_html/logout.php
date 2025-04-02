<?php
// logout.php - Versión optimizada

// 1. Configuración de sesión segura
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start([
        'cookie_lifetime' => 0,
        'use_strict_mode' => true,
        'cookie_httponly' => true,
        'cookie_secure' => false, // Cambiar a true en producción con HTTPS
        'use_only_cookies' => 1
    ]);
}

// 2. Destrucción completa de la sesión
$_SESSION = array();

// Eliminar cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Destruir y cerrar sesión
session_destroy();

// 4. Redirección con header más eficiente
header("Location: login.php");
exit;
