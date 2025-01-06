<?php
// Incluir la conexión a la base de datos
include 'bd.php';

// Configuración para exportar un archivo Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=tickets_exportados.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Consulta a la base de datos
$consulta = "SELECT * FROM tickets";
$result = $conn->query($consulta);

// Validar si hay datos
if ($result->num_rows > 0) {
    // Imprimir encabezados de las columnas
    echo "ID\tFecha\tSerie\tEstado\tNombre\tAsunto\tTécnico\tPrioridad\n";

    // Imprimir datos fila por fila
    while ($row = $result->fetch_assoc()) {
        echo "{$row['id']}\t{$row['fecha']}\t{$row['serie']}\t{$row['estado']}\t{$row['nombre']}\t{$row['asunto']}\t{$row['tecnico']}\t{$row['prioridad']}\n";
    }
} else {
    echo "No hay datos disponibles";
}
?>
