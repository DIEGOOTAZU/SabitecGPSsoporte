<?php
// Credenciales de la base de datos proporcionadas por Railway
$host = "mysql-pqrr.railway.internal"; // Este es el host proporcionado por Railway
$user = "root";                        // Usuario proporcionado
$password = "cOMOmOXGoBmaPFMimisuzEQVPyzznWf"; // Contraseña proporcionada
$database = "railway";                 // Nombre de la base de datos
$port = 3306;                          // Puerto proporcionado

try {
    // Crear conexión
    $conn = new mysqli($host, $user, $password, $database, $port);

    // Verificar conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    // Establecer el conjunto de caracteres a UTF-8
    if (!$conn->set_charset("utf8")) {
        throw new Exception("Error configurando el conjunto de caracteres UTF-8: " . $conn->error);
    }

    echo "Conexión exitosa a la base de datos: $database";
} catch (Exception $e) {
    die($e->getMessage());
}
?>
