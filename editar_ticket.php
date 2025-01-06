<?php
// Incluir conexión a la base de datos
include 'bd.php';

// Verificar si se recibió un ID de ticket
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p style='color: red;'>ID de ticket no válido.</p>";
    exit();
}

$id = $_GET['id'];

// Consultar los datos del ticket
$sql = "SELECT * FROM tickets WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p style='color: red;'>No se encontró el ticket.</p>";
    exit();
}

$ticket = $result->fetch_assoc();
$stmt->close();

// Verificar si se envió el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'];
    $estado = $_POST['estado'];
    $nombre = $_POST['nombre'];
    $tecnico = $_POST['tecnico'];
    $prioridad = $_POST['prioridad'];
    $asunto = $_POST['asunto'];
    $mensaje = $_POST['mensaje'];
    $solucion = $_POST['solucion'];
    $tiempo_solucion = $_POST['tiempo_solucion'];

    // Actualizar el ticket en la base de datos
    $update_sql = "UPDATE tickets SET fecha = ?, estado = ?, nombre = ?, tecnico = ?, prioridad = ?, asunto = ?, problema = ?, solucion = ?, tiempo_solucion = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssssssi", $fecha, $estado, $nombre, $tecnico, $prioridad, $asunto, $mensaje, $solucion, $tiempo_solucion, $id);

    if ($stmt->execute()) {
        // Redirigir con un mensaje de éxito
        header("Location: index.php?update=success");
        exit();
    } else {
        echo "<p style='color: red;'>Error al actualizar el ticket: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ticket</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            font-family: Arial, sans-serif;
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

        .header-container nav {
            color: white;
            margin: 0 10px;
            text-decoration: none;
            font-size: 16px;
        }

        .header-container nav a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
            font-size: 16px;
        }

        .header-container nav a:hover {
            text-decoration: underline;
        }

        .form-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: 30px auto;
            max-width: 800px;
        }

        .form-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        form input, form textarea, form select {
            display: block;
            width: 80%;
            margin: 10px auto;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .d-grid button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container d-flex justify-content-between align-items-center">
            <h1>SabitecGPS</h1>
            <nav>
                <a href="index.php">Inicio</a>
                <a href="soporte.php">Soporte Técnico</a>
                <a href="nuevo_ticket.php">Nuevo Ticket</a>
            </nav>
        </div>
    </header>
    <main class="container">
        <div class="form-container">
            <h2>Editar Ticket</h2>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="fecha">Fecha:</label>
                    <input type="date" id="fecha" name="fecha" value="<?php echo $ticket['fecha']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="serie">Serie:</label>
                    <input type="text" id="serie" name="serie" value="<?php echo $ticket['serie']; ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="estado">Estado:</label>
                    <select id="estado" name="estado" required>
                        <option value="Pendiente" <?php echo $ticket['estado'] === 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="En Proceso" <?php echo $ticket['estado'] === 'En Proceso' ? 'selected' : ''; ?>>En Proceso</option>
                        <option value="Resuelto" <?php echo $ticket['estado'] === 'Resuelto' ? 'selected' : ''; ?>>Resuelto</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $ticket['nombre']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tecnico" class="form-label">Técnico:</label>
                    <select id="tecnico" name="tecnico" class="form-select" required>
                        <option value="Ruben" <?php echo $ticket['tecnico'] === 'Ruben' ? 'selected' : ''; ?>>Ruben</option>
                        <option value="Diego" <?php echo $ticket['tecnico'] === 'Diego' ? 'selected' : ''; ?>>Diego</option>
                        <option value="Carlos" <?php echo $ticket['tecnico'] === 'Carlos' ? 'selected' : ''; ?>>Carlos</option>
                        <option value="Fran" <?php echo $ticket['tecnico'] === 'Fran' ? 'selected' : ''; ?>>Fran</option>
                        <option value="Miguel" <?php echo $ticket['tecnico'] === 'Miguel' ? 'selected' : ''; ?>>Miguel</option>
                        <option value="Marcos" <?php echo $ticket['tecnico'] === 'Marcos' ? 'selected' : ''; ?>>Marcos</option>
                        <option value="Moises" <?php echo $ticket['tecnico'] === 'Moises' ? 'selected' : ''; ?>>Moises</option>
                        <option value="Gian" <?php echo $ticket['tecnico'] === 'Gian' ? 'selected' : ''; ?>>Gian</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="prioridad">Prioridad:</label>
                    <input type="text" id="prioridad" name="prioridad" value="<?php echo $ticket['prioridad']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="asunto">Asunto:</label>
                    <input type="text" id="asunto" name="asunto" value="<?php echo $ticket['asunto']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="mensaje">Mensaje:</label>
                    <textarea id="mensaje" name="mensaje" required><?php echo $ticket['problema']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="solucion">Solución:</label>
                    <textarea id="solucion" name="solucion"><?php echo isset($ticket['solucion']) ? $ticket['solucion'] : ''; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="tiempo_solucion">Tiempo de Solución (HH:MM:SS):</label>
                    <input type="time" step="1" id="tiempo_solucion" name="tiempo_solucion" value="<?php echo isset($ticket['tiempo_solucion']) ? $ticket['tiempo_solucion'] : ''; ?>" required>
                </div>
                <div class="d-grid">
                    <button type="submit">Actualizar Ticket</button>
                </div>
            </form>
       
