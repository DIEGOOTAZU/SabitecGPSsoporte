<?php
// Incluir conexión a la base de datos
include 'bd.php';

session_start();
// Verificar si el usuario está logueado
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

// Consulta por defecto
$consulta = "SELECT * FROM tickets";

// Verificar si se enviaron filtros (fechas, estado o nombre)
$filters = [];
if (isset($_GET['from-date']) && isset($_GET['to-date']) && !empty($_GET['from-date']) && !empty($_GET['to-date'])) {
    $from_date = $conn->real_escape_string($_GET['from-date']);
    $to_date = $conn->real_escape_string($_GET['to-date']);
    $filters[] = "fecha BETWEEN '$from_date' AND '$to_date'";
}
if (isset($_GET['estado']) && !empty($_GET['estado'])) {
    $estado = $conn->real_escape_string($_GET['estado']);
    $filters[] = "estado = '$estado'";
}
if (isset($_GET['nombre']) && !empty($_GET['nombre'])) {
    $nombre = $conn->real_escape_string($_GET['nombre']);
    $filters[] = "nombre LIKE '%$nombre%'";
}

// Construir la consulta final con filtros
if (count($filters) > 0) {
    $consulta .= ' WHERE ' . implode(' AND ', $filters);
}

// Agregar orden descendente por fecha y hora para mostrar los más recientes primero
$consulta .= ' ORDER BY fecha DESC, hora_creacion DESC';

// Ejecutar consulta
$result = $conn->query($consulta);

// Validar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta SQL: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - SabitecGPS</title>
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

    .header-container nav input[type="text"] {
        padding: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .header-container nav button {
        padding: 5px 10px;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        color: white;
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

    .filter-container {
        margin: 20px auto;
        text-align: center;
    }

    .filter-container form,
    .filter-container a {
        display: inline-block;
        margin: 0 10px;
    }

    .filter-container input[type="date"], .filter-container select {
        padding: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .btn-primary {
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: white;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
        text-align: left;
        background-color: white;
        border-radius: 5px;
        overflow: hidden;
    }

    table thead {
        background-color: #343a40;
        color: white;
    }

    table th,
    table td {
        padding: 10px;
        border: 1px solid #ddd;
    }

    table tr:hover {
        background-color: #f1f1f1;
    }

    h2 {
        text-align: center;
        margin: 20px 0;
    }

    .content h3 {
        width: 100%;
        text-align: center;
    }

    .content p {
        width: 100%;
        text-align: center;
    }

    .export-button {
        margin: 20px auto 0;
        display: flex;
        justify-content: flex-start;
        width: 90%;
    }
    </style>
    <script>
        function confirmarEliminacion(event, url) {
            event.preventDefault();
            if (confirm("¿Estás seguro de que deseas eliminar este ticket?")) {
                window.location.href = url;
            }
        }
    </script>
</head>
<body>
<header>
    <div class="header-container">
        <h1>SabitecGPS</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="soporte.php">Soporte técnico</a>
            <a href="logout.php" class="btn btn-secondary">Cerrar Sesión</a>
        </nav>
    </div>
</header>
<main>
    <h2>Panel Administrativo</h2>
    <div class="filter-container">
        <form method="GET" action="" style="display: inline-block;">
            <div style="display: inline-block; margin-right: 10px;">
                <label for="from-date">Desde:</label>
                <input type="date" id="from-date" name="from-date" value="<?php echo isset($_GET['from-date']) ? $_GET['from-date'] : ''; ?>">
            </div>
            <div style="display: inline-block; margin-right: 10px;">
                <label for="to-date">Hasta:</label>
                <input type="date" id="to-date" name="to-date" value="<?php echo isset($_GET['to-date']) ? $_GET['to-date'] : ''; ?>">
            </div>
            <div style="display: inline-block; margin-right: 10px;">
                <label for="estado">Estado:</label>
                <select id="estado" name="estado">
                    <option value="" <?php echo empty($_GET['estado']) ? 'selected' : ''; ?>>Todos</option>
                    <option value="Pendiente" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="Resuelto" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'Resuelto') ? 'selected' : ''; ?>>Resuelto</option>
                </select>
            </div>
            <div style="display: inline-block; margin-right: 10px;">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Buscar por nombre" value="<?php echo isset($_GET['nombre']) ? $_GET['nombre'] : ''; ?>">

            </div>
            <button type="submit" class="btn btn-primary" style="margin-right: 10px; padding: 8px 15px;">Filtrar</button>
        </form>
        <a href="index.php" class="btn btn-primary" style="text-decoration: none; color: white; padding: 8px 15px; border-radius: 5px; background-color: #007bff; border: none; display: inline-block;">Borrar Filtro</a>
    </div>

    <div class="export-button">
        <a href="exportar_excel.php" class="btn btn-primary">Exportar Tickets</a>
    </div>

    <section class="content">
        <h3>Bienvenido <?php echo $_SESSION['username']; ?></h3>
        <p>Aquí se muestran todos los Tickets de soporte de SabitecGPS.</p>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Serie</th>
                    <th>Estado</th>
                    <th>Nombre</th>
                    <th>Asunto</th>
                    <th>Técnico</th>
                    <th>Prioridad</th>
                    <th>Solución</th>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <th>Opciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar resultados de la consulta
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['fecha']}</td>
                            <td>{$row['hora_creacion']}</td>
                            <td>{$row['serie']}</td>
                            <td>{$row['estado']}</td>
                            <td>{$row['nombre']}</td>
                            <td>{$row['asunto']}</td>
                            <td>{$row['tecnico']}</td>
                            <td>{$row['prioridad']}</td>
                            <td>{$row['solucion']}</td>";
                

                        if ($_SESSION['role'] === 'admin') {
                            echo "<td>
                                <a href='editar_ticket.php?id={$row['id']}' class='btn btn-primary'>Editar</a>
                                <a href='#' onclick=\"confirmarEliminacion(event, 'eliminar_ticket.php?id={$row['id']}')\" class='btn btn-danger'>Eliminar</a>";

                            // Agregar un check verde si el estado es 'Resuelto'
                            if ($row['estado'] === 'Resuelto') {
                                echo " <span style='color: green; font-size: 1.2em;'>&#10003;</span>";
                            }

                            echo "</td>";
                        }

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No hay tickets</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</main>
</body>
</html>
