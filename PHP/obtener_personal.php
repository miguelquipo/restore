<?php
// archivo: obtener_productos.php
include 'db.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}


$sql = "SELECT id_trabajador,nombre,apellido,cedula FROM Trabajadores";
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