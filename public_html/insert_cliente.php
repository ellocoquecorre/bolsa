<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "incitato";
$dbname = "goodfellas";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Datos a insertar
$clientes = [
    [
        'cliente_id' => 3,
        'email' => 'roberto@gmail.com',
        'password' => 'tgy107Duna',
        'nombre' => 'Roberto',
        'apellido' => 'Enano',
        'telefono' => '654321',
        'corredora' => 'Allaria',
        'url' => 'https://allaria.com.ar/'
    ]
];

// Insertar datos
foreach ($clientes as $cliente) {
    // Hashear la contraseña
    $hashed_password = password_hash($cliente['password'], PASSWORD_BCRYPT);

    // Consulta SQL para insertar datos
    $sql = "INSERT INTO clientes (cliente_id, email, password, nombre, apellido, telefono, corredora, url) VALUES (
        {$cliente['cliente_id']}, 
        '{$cliente['email']}', 
        '$hashed_password', 
        '{$cliente['nombre']}', 
        '{$cliente['apellido']}', 
        '{$cliente['telefono']}', 
        '{$cliente['corredora']}', 
        '{$cliente['url']}'
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Nuevo registro creado exitosamente para el cliente con ID {$cliente['cliente_id']}<br>";

        // Consulta SQL para insertar datos en la tabla balance
        $balance_sql = "INSERT INTO balance (cliente_id, efectivo) VALUES (3, 15000000)";
        if ($conn->query($balance_sql) === TRUE) {
            echo "Nuevo registro de balance creado exitosamente para el cliente con ID 3<br>";
        } else {
            echo "Error: " . $balance_sql . "<br>" . $conn->error . "<br>";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
    }
}

// Cerrar conexión
$conn->close();
