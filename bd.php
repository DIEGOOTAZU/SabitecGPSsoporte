<?php

// Obtener la URL de conexión desde la variable de entorno
$db_url = getenv('DATABASE_URL');

// Parsear la URL de conexión
$parts = parse_url($db_url);

$host = "localhost";
$user = "root";
$password = "";
$database = "sabitecgps";
$port = 3306; // MYSQLPORT
try {
    // Crear conexión
    $conn = new mysqli($host, $user, $password, $database);

    // Verificar conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    // Establecer el conjunto de caracteres a UTF-8
    if (!$conn->set_charset("utf8")) {
        throw new Exception("Error configurando el conjunto de caracteres UTF-8: " . $conn->error);
    }
} catch (Exception $e) {
    die($e->getMessage());
}
?>

