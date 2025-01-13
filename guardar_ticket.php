<?php
// Incluir conexión a la base de datos
include 'bd.php';

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'];
    $nombre = $_POST['nombre'];
    $tecnico = $_POST['tecnico'];
    $prioridad = $_POST['prioridad'];
    $asunto = $_POST['asunto'];
    $problema = $_POST['problema'];
    $tiempo_solucion = $_POST['tiempo_solucion'];
    $hora_creacion = $_POST['hora_creacion']; // Hora enviada automáticamente

    // Generar serie aleatoria (formato: TK##L#)
    $serie = 'TK' . rand(10, 99) . chr(rand(65, 90)) . rand(1, 9);

    // Configurar estado como "Pendiente"
    $estado = 'Pendiente';

    try {
        // Insertar datos en la base de datos con PDO
        $sql = "INSERT INTO tickets (fecha, nombre, tecnico, prioridad, asunto, problema, tiempo_solucion, serie, estado, hora_creacion) 
                VALUES (:fecha, :nombre, :tecnico, :prioridad, :asunto, :problema, :tiempo_solucion, :serie, :estado, :hora_creacion)";
        $stmt = $conn->prepare($sql);

        // Vincular los parámetros
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':tecnico', $tecnico, PDO::PARAM_STR);
        $stmt->bindParam(':prioridad', $prioridad, PDO::PARAM_STR);
        $stmt->bindParam(':asunto', $asunto, PDO::PARAM_STR);
        $stmt->bindParam(':problema', $problema, PDO::PARAM_STR);
        $stmt->bindParam(':tiempo_solucion', $tiempo_solucion, PDO::PARAM_STR);
        $stmt->bindParam(':serie', $serie, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':hora_creacion', $hora_creacion, PDO::PARAM_STR);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir al formulario con un mensaje de éxito
            header("Location: nuevo_ticket.php?success=1");
            exit();
        } else {
            echo "<p style='color: red;'>Error al guardar el ticket.</p>";
        }
    } catch (PDOException $e) {
        // Manejo de errores
        echo "<p style='color: red;'>Error al guardar el ticket: " . $e->getMessage() . "</p>";
    }
}
?>
