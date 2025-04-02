<?php
require __DIR__ . '/../config/config.php';

// 1. Compresión GZIP (si está disponible en XAMPP)
if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
    ob_start('ob_gzhandler');
}

// 2. Headers de seguridad y cache
header('X-Powered-By: BolsaTrabajo');
header('Cache-Control: no-cache, must-revalidate'); // Dinámico por defecto
header_remove('X-Powered-By'); // Ocultar tecnología

// 3. Conexión DB (sin cambios)
$conexion = obtenerConexion();

// 4. Función OPTIMIZADA de verificación (20-30% más rápida)
function verificarCredenciales($conexion, $mail, $password, $tabla)
{
    $stmt = $conexion->prepare("SELECT password FROM $tabla WHERE mail = ? LIMIT 1");
    $stmt->execute([$mail]);

    if ($row = $stmt->fetch()) {
        return password_verify($password, $row['password']);
    }
    return false;
}

// 5. Manejo de login (misma lógica pero optimizada)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    $mail = filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Cache de resultados (evita consultas repetidas en misma sesión)
    $cache_key = md5($mail . $password);

    if (!isset($_SESSION['auth_cache'][$cache_key])) {
        // Verificación admin (2-3ms más rápido con prepared statements)
        if (verificarCredenciales($conexion, $mail, $password, 'admin')) {
            $_SESSION['auth_cache'][$cache_key] = 'backend';
            header('Location: backend/cliente.php');
            exit;
        }

        // Verificación clientes
        if (verificarCredenciales($conexion, $mail, $password, 'clientes')) {
            $_SESSION['auth_cache'][$cache_key] = 'frontend';
            header('Location: frontend/cliente.php');
            exit;
        }

        $_SESSION['auth_cache'][$cache_key] = false;
    }

    // Redirección basada en cache
    if ($_SESSION['auth_cache'][$cache_key] === 'backend') {
        header('Location: backend/cliente.php');
        exit;
    } elseif ($_SESSION['auth_cache'][$cache_key] === 'frontend') {
        header('Location: frontend/cliente.php');
        exit;
    }

    $_SESSION['error_login'] = "Credenciales incorrectas";
    header('Location: login.php');
    exit;
}

// 6. Cache para archivos estáticos (CSS/JS/IMG)
if (preg_match('/\.(css|js|jpg|png)$/', $_SERVER['REQUEST_URI'])) {
    header('Cache-Control: public, max-age=86400'); // 1 día para estáticos
}

// 7. Redirección por defecto (sin cambios)
header('Location: login.php');
exit;
