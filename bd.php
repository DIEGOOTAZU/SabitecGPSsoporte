<?php
// Configuración de la base de datos PostgreSQL en Render
$host = 'dpg-cu0nbb9u0jms73dt194g-a'; // Reemplaza con el "Hostname" de Render
$dbname = 'gestion_tickets_ntnf';     // Reemplaza con el "Database" de Render
$username = 'gestion_tickets_ntnf_user'; // Reemplaza con el "Username" de Render
$password = '5GF36pn6ABS8EiDVjUmA9yq0s5kynhEN'; // Reemplaza con el "Password" de Render
$port = 5432;                         // Reemplaza con el "Port" de Render

try {
    // Crear la conexión a la base de datos con PDO
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    // Configurar el modo de error de PDO para excepciones
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Establecer el conjunto de caracteres a UTF-8 (opcional en PDO)
    $conn->exec("SET NAMES 'UTF8'");
    // echo "Conexión exitosa a PostgreSQL.";
} catch (PDOException $e) {
    // Manejo de errores en caso de falla de conexión
    die("Error de conexión: " . $e->getMessage());
}
?>
