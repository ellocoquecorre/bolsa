<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Envío de Correo</title>
</head>

<body>
    <h1>Prueba de Envío de Correo</h1>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $to = $_POST['email'];
        $subject = "Prueba de Envío de Correo";
        $message = "Este es un mensaje de prueba enviado desde un script PHP.";
        $headers = "From: paimarino@gmail.com";

        if (mail($to, $subject, $message, $headers)) {
            echo "<p>Correo enviado exitosamente a $to.</p>";
        } else {
            echo "<p>Error al enviar el correo.</p>";
        }
    }
    ?>
    <form method="post" action="">
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" id="email" required>
        <input type="submit" value="Enviar">
    </form>
</body>

</html>