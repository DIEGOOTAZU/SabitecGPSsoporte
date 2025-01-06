<?php
// Incluir conexión a la base de datos
include 'bd.php';

// Verificar si se recibió el ID del ticket
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar el ticket de la base de datos
    $sql = "DELETE FROM tickets WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirigir a la página principal con un mensaje de éxito
        header("Location: index.php?delete=success");
        exit();
    } else {
        echo "<p style='color: red;'>Error al eliminar el ticket: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p style='color: red;'>ID de ticket no válido.</p>";
    exit();
}
?>
