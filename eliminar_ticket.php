<?php
// Incluir conexión a la base de datos
include 'bd.php';

// Verificar si se recibió el ID del ticket
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Preparar la consulta para eliminar el ticket
        $sql = "DELETE FROM tickets WHERE id = :id";
        $stmt = $conn->prepare($sql);
        
        // Vincular el parámetro
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir a la página principal con un mensaje de éxito
            header("Location: index.php?delete=success");
            exit();
        } else {
            echo "<p style='color: red;'>Error al eliminar el ticket.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Error en la consulta: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>ID de ticket no válido.</p>";
    exit();
}
?>
