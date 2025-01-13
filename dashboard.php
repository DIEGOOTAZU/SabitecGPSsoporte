<?php
// Incluir conexión a la base de datos
include 'bd.php';

// Inicializar sesión
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

// Obtener los filtros desde la solicitud GET
$nombre_filter = $_GET['nombre'] ?? '';
$estado_filter = $_GET['estado'] ?? '';
$tecnico_filter = $_GET['tecnico'] ?? '';
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

// Construir la consulta base con filtros
$filters = [];
$params = [];
if (!empty($nombre_filter)) {
    $filters[] = "nombre = :nombre";
    $params[':nombre'] = $nombre_filter;
}
if (!empty($estado_filter)) {
    $filters[] = "estado = :estado";
    $params[':estado'] = $estado_filter;
}
if (!empty($tecnico_filter)) {
    $filters[] = "tecnico = :tecnico";
    $params[':tecnico'] = $tecnico_filter;
}
if (!empty($from_date) && !empty($to_date)) {
    $filters[] = "fecha BETWEEN :from_date AND :to_date";
    $params[':from_date'] = $from_date;
    $params[':to_date'] = $to_date;
}

// Construir cláusula WHERE
$where_clause = count($filters) > 0 ? 'WHERE ' . implode(' AND ', $filters) : '';

// Consultas ajustadas con verificación de $where_clause
$total_tickets_query = "SELECT COUNT(*) as total FROM tickets $where_clause";
$pending_tickets_query = "SELECT COUNT(*) as pendientes FROM tickets " . ($where_clause ? "$where_clause AND" : "WHERE") . " estado = 'Pendiente'";
$resolved_tickets_query = "SELECT COUNT(*) as resueltos FROM tickets " . ($where_clause ? "$where_clause AND" : "WHERE") . " estado = 'Resuelto'";

// Preparar y ejecutar consultas
$stmt_total = $conn->prepare($total_tickets_query);
$stmt_pending = $conn->prepare($pending_tickets_query);
$stmt_resolved = $conn->prepare($resolved_tickets_query);

// Vincular parámetros y ejecutar
foreach ($params as $key => $value) {
    $stmt_total->bindValue($key, $value);
    $stmt_pending->bindValue($key, $value);
    $stmt_resolved->bindValue($key, $value);
}

$stmt_total->execute();
$stmt_pending->execute();
$stmt_resolved->execute();

// Obtener resultados
$total_tickets = $stmt_total->fetchColumn() ?? 0;
$pending_tickets = $stmt_pending->fetchColumn() ?? 0;
$resolved_tickets = $stmt_resolved->fetchColumn() ?? 0;

// Datos para gráficos
$resolved_tickets_data_query = "SELECT nombre, tiempo_solucion FROM tickets " . ($where_clause ? "$where_clause AND" : "WHERE") . " estado = 'Resuelto'";
$stmt_resolved_data = $conn->prepare($resolved_tickets_data_query);
foreach ($params as $key => $value) {
    $stmt_resolved_data->bindValue($key, $value);
}
$stmt_resolved_data->execute();

$names = [];
$times = [];
while ($row = $stmt_resolved_data->fetch(PDO::FETCH_ASSOC)) {
    $names[] = $row['nombre'];
    $time_parts = explode(':', $row['tiempo_solucion']);
    $minutes = $time_parts[0] * 60 + $time_parts[1]; // Convertir HH:MM:SS a minutos
    $times[] = $minutes;
}

// Obtener opciones únicas para el campo nombre
$unique_names_query = "SELECT DISTINCT nombre FROM tickets";
$unique_names_result = $conn->query($unique_names_query);
$unique_names = $unique_names_result->fetchAll(PDO::FETCH_COLUMN);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SabitecGPS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header-container {
            background-color: #343a40;
            padding: 10px 20px;
            color: white;
        }
        .header-container h1 {
            font-size: 24px;
            display: inline-block;
        }
        .header-container nav a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
        }
        .main-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin: 20px;
        }
        .filters {
            flex: 0 0 20%;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .filters h5 {
            margin-bottom: 15px;
        }
        .filters label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .filters select, .filters input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .dashboard-cards {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px;
        }
        .card {
            flex: 1;
            padding: 20px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin: 20px auto;
            width: 90%;
        }
        .chart {
            flex: 1;
            max-width: 45%;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        canvas {
            max-width: 100%;
            max-height: 300px;
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
        </nav>
    </div>
</header>
<main class="main-container">
    <aside class="filters">
        <h5>Filtros</h5>
        <form method="GET" action="dashboard.php">
            <label for="nombre">Nombre</label>
            <input list="nombres" id="nombre" name="nombre" placeholder="Filtrar por nombre" value="<?php echo htmlspecialchars($nombre_filter); ?>">
            <datalist id="nombres">
                <?php foreach ($unique_names as $name): ?>
                    <option value="<?php echo htmlspecialchars($name); ?>"></option>
                <?php endforeach; ?>
            </datalist>

            <label for="estado">Estado</label>
            <select id="estado" name="estado">
                <option value="" <?php echo empty($estado_filter) ? 'selected' : ''; ?>>Todos</option>
                <option value="Pendiente" <?php echo $estado_filter === 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                <option value="En Proceso" <?php echo $estado_filter === 'En Proceso' ? 'selected' : ''; ?>>En Proceso</option>
                <option value="Resuelto" <?php echo $estado_filter === 'Resuelto' ? 'selected' : ''; ?>>Resuelto</option>
            </select>

            <label for="tecnico">Técnico</label>
            <select id="tecnico" name="tecnico">
                <option value="" <?php echo empty($tecnico_filter) ? 'selected' : ''; ?>>Todos</option>
                <option value="Carlos" <?php echo $tecnico_filter === 'Carlos' ? 'selected' : ''; ?>>Carlos</option>
            </select>

            <label for="from_date">Desde</label>
            <input type="date" id="from_date" name="from_date" value="<?php echo htmlspecialchars($from_date); ?>">

            <label for="to_date">Hasta</label>
            <input type="date" id="to_date" name="to_date" value="<?php echo htmlspecialchars($to_date); ?>">

            <button type="submit" class="btn btn-primary">Aplicar</button>
            <a href="dashboard.php" class="btn btn-secondary">Borrar Filtros</a>
        </form>
    </aside>
    <section style="flex: 1;">
        <div class="dashboard-cards">
            <div class="card">
                <h5>Total de Tickets</h5>
                <p><?php echo $total_tickets; ?></p>
            </div>
            <div class="card">
                <h5>Tickets Pendientes</h5>
                <p><?php echo $pending_tickets; ?></p>
                <small><?php echo round(($pending_tickets / $total_tickets) * 100, 2); ?>% del total</small>
            </div>
            <div class="card">
                <h5>Tickets Resueltos</h5>
                <p><?php echo $resolved_tickets; ?></p>
                <small><?php echo round(($resolved_tickets / $total_tickets) * 100, 2); ?>% del total</small>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart">
                <canvas id="ticketsChart"></canvas>
            </div>
            <div class="chart">
                <canvas id="barsChart"></canvas>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart">
                <canvas id="horizontalBarsChart"></canvas>
            </div>
        </div>
    </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de Tickets
    const ticketsCtx = document.getElementById('ticketsChart').getContext('2d');
    new Chart(ticketsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pendientes', 'Resueltos'],
            datasets: [{
                data: [<?php echo $pending_tickets; ?>, <?php echo $resolved_tickets; ?>],
                backgroundColor: ['#ffc107', '#28a745']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });

    // Gráfico de Barras
    const barsCtx = document.getElementById('barsChart').getContext('2d');
    new Chart(barsCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($names); ?>,
            datasets: [{
                label: 'Tiempo de Solución (minutos)',
                data: <?php echo json_encode($times); ?>,
                backgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return `${value} min`;
                        }
                    }
                }
            }
        }
    });

    // Gráfico de Barras Horizontales
    const horizontalBarsCtx = document.getElementById('horizontalBarsChart').getContext('2d');
    new Chart(horizontalBarsCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($horizontal_names); ?>,
            datasets: [{
                label: 'Unidades repetidas',
                data: <?php echo json_encode($horizontal_counts); ?>,
                backgroundColor: '#ff5733'
            }]
        },
        options: {
            indexAxis: 'y', // Cambiar a barras horizontales
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>
