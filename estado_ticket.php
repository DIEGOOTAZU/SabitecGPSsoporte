<?php
// Incluir conexión a la base de datos
include 'bd.php';

session_start();

// Verificar si se envió el ID del ticket
if (!isset($_POST['ticket_id']) || empty($_POST['ticket_id'])) {
    die("El ID del ticket es obligatorio.");
}

$ticket_id = $conn->real_escape_string($_POST['ticket_id']);

// Consultar datos del ticket
$query = "SELECT * FROM tickets WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró el ticket
if ($result->num_rows === 0) {
    die("No se encontró un ticket con ese ID.");
}

$ticket = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado del Ticket - SabitecGPS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header-container {
            background-color: #343a40;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-container h1 {
            font-size: 24px;
            margin: 0;
        }
        .header-container nav a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
            font-size: 16px;
        }
        .ticket-details {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            padding: 40px;
            margin-top: 30px;
        }
        .ticket-details h3 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .ticket-details p {
            font-size: 16px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<header>
    <div class="header-container">
        <h1>SabitecGPS</h1>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="soporte.php">Soporte Técnico</a>
        </nav>
    </div>
</header>

<main class="container">
    <div class="ticket-details">
        <h3>Detalles del Ticket</h3>
        <p><strong>ID:</strong> <?php echo $ticket['id']; ?></p>
        <p><strong>Fecha:</strong> <?php echo $ticket['fecha']; ?></p>
        <p><strong>Estado:</strong> <?php echo $ticket['estado']; ?></p>
        <p><strong>Nombre:</strong> <?php echo $ticket['nombre']; ?></p>
        <p><strong>Asunto:</strong> <?php echo $ticket['asunto']; ?></p>
        <p><strong>Técnico:</strong> <?php echo $ticket['tecnico']; ?></p>
        <p><strong>Prioridad:</strong> <?php echo $ticket['prioridad']; ?></p>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
