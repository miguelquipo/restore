<?php
// archivo: obtener_productos.php
include 'db.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta SQL para obtener los productos
$sql = "SELECT id_producto, nombre_producto, rendimiento_producto_hora FROM productos";
$result = $conn->query($sql);

$productos = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}

// Enviar los datos como JSON
header('Content-Type: application/json');
echo json_encode($productos);

$conn->close();
?>