<?php
// CONFIGURACIÓN CORREGIDA PARA XAMPP

// 1. Verificar si la sesión está activa antes de configurarla
if (session_status() === PHP_SESSION_NONE) {
    // Configuración de sesión SEGURA para XAMPP
    ini_set('session.cookie_lifetime', 0);
    ini_set('session.cache_limiter', 'nocache');
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0);  // IMPORTANTE: 0 en XAMPP sin HTTPS
    ini_set('session.gc_maxlifetime', 1800);

    // Iniciar sesión solo si no está activa
    session_start();
}

// 2. Manejo de errores (configuración para XAMPP)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__DIR__) . '/logs/php_errors.log');

// 3. Zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

// 4. Headers de seguridad BÁSICOS para desarrollo
header("X-Content-Type-Options: nosniff");

// 5. Conexión a DB optimizada para XAMPP
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'incitato';
$db_name = 'goodfellas';

static $dbh = null;
if ($dbh === null) {
    try {
        $dbh = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ]);
    } catch (PDOException $e) {
        error_log("Error de conexión en " . date('Y-m-d H:i:s') . ": " . $e->getMessage());
        die('<div style="color: #721c24; background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; font-family: Arial;">
            <strong>Error de conexión:</strong> Verifica los logs o contacta al administrador.<br>
            <small>Detalle técnico: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</small>
            </div>');
    }
}

// 6. Función original (sin cambios)
function obtenerConexion()
{
    global $dbh;
    return $dbh;
}

// 7. Rutas compatibles con Windows/XAMPP
define('ROOT_PATH', str_replace('\\', '/', dirname(__DIR__)));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public_html');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// 8. Optimización para XDebug (útil en VS Code)
if (function_exists('xdebug_disable')) {
    if (function_exists('xdebug_disable')) {
        xdebug_disable();
    }
}
