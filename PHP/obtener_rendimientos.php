<?php
// Incluir tu archivo de conexión a la base de datos
include 'db.php';

// Realizar la consulta SQL para obtener los datos agrupados
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
WHERE rendimiento.hora_registro BETWEEN DATE_SUB(NOW(), INTERVAL 1 HOUR) AND NOW() -- Filtrar por la última hora
GROUP BY productos.id_producto, productos.nombre_producto, trabajadores.nombre, trabajadores.apellido;
";

$result = $conn->query($sql);

$rendimientos = [];

if ($result->num_rows > 0) {
    // Guardar los datos en un array asociativo
    while ($row = $result->fetch_assoc()) {
        $rendimientos[] = $row;
    }
}

// Cerrar la conexión
$conn->close();

// Establecer el encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json');

// Imprimir los datos en formato JSON
echo json_encode($rendimientos);
?>
