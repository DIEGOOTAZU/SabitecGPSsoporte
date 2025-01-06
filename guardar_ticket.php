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

    // Insertar datos en la base de datos
    $sql = "INSERT INTO tickets (fecha, nombre, tecnico, prioridad, asunto, problema, tiempo_solucion, serie, estado, hora_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $fecha, $nombre, $tecnico, $prioridad, $asunto, $problema, $tiempo_solucion, $serie, $estado, $hora_creacion);

    if ($stmt->execute()) {
        // Redirigir al formulario con un mensaje de éxito
        header("Location: nuevo_ticket.php?success=1");
        exit();
    } else {
        echo "<p style='color: red;'>Error al guardar el ticket: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
