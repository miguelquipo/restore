<?php
include 'db.php';

// Obtener las fechas enviadas desde el formulario
$startDate = $_GET['start_date'];
$endDate = $_GET['end_date'];

// Realizar la consulta SQL con el filtro de fechas
$sql = "SELECT
    productos.id_producto as id_rendimiento,
    SUM(rendimiento.cantidad_vendida) AS cantidad_vendida,
    productos.nombre_producto,
    trabajadores.nombre AS nombre_trabajador,
    trabajadores.apellido AS apellido_trabajador,
    MAX(rendimiento.fecha_registro) AS fecha_registro,
    MAX(rendimiento.hora_registro) AS hora_registro
    FROM rendimiento
    INNER JOIN productos ON rendimiento.id_producto = productos.id_producto
    INNER JOIN trabajadores ON rendimiento.id_trabajador = trabajadores.id_trabajador
    WHERE rendimiento.fecha_registro BETWEEN '$startDate' AND '$endDate'
    GROUP BY productos.id_producto, productos.nombre_producto, trabajadores.nombre, trabajadores.apellido
";

$result = $conn->query($sql);

$rendimientos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rendimientos[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($rendimientos);
?>
