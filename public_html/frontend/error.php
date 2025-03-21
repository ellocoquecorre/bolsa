<?php
// Iniciar sesión para mostrar un mensaje adecuado en caso de que el usuario intente acceder sin permisos
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de acceso</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container text-center">
        <h1>Acceso no autorizado</h1>
        <p>No tienes permiso para acceder a esta página.</p>
        <a href="../login.php">Volver a Iniciar Sesión</a>
    </div>
</body>

</html>