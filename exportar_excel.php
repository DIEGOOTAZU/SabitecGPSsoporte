<?php
// Incluir la conexión a la base de datos
include 'bd.php';

try {
    // Configuración para exportar un archivo Excel
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=tickets_exportados.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Consulta a la base de datos
    $consulta = "SELECT * FROM tickets";
    $stmt = $conn->prepare($consulta);
    $stmt->execute();

    // Validar si hay datos
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows) > 0) {
        // Imprimir encabezados de las columnas
        echo "ID\tFecha\tHora\tSerie\tEstado\tNombre\tAsunto\tTecnico\tPrioridad\tSolucion\n";

        // Imprimir datos fila por fila
        foreach ($rows as $row) {
            echo "{$row['id']}\t{$row['fecha']}\t{$row['hora_creacion']}\t{$row['serie']}\t{$row['estado']}\t{$row['nombre']}\t{$row['asunto']}\t{$row['tecnico']}\t{$row['prioridad']}\t{$row['solucion']}\n";
        }
    } else {
        echo "No hay datos disponibles";
    }
} catch (PDOException $e) {
    echo "Error al exportar: " . $e->getMessage();
}
?>
