<?php
// Realiza la conexión a la base de datos (cambia estos valores por los tuyos)
include 'db.php';
var_dump($_POST);

// Verifica si se ha enviado una solicitud POST y si los campos del formulario están definidos
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search-cedula']) && isset($_POST['search-producto']) && isset($_POST['insert-cantidad'])) {
    // Obtén los valores de los campos del formulario
    $cedula = $_POST['search-cedula'];
    $codigoProducto = $_POST['search-producto'];
    $cantidadVendida = $_POST['insert-cantidad'];

    // Función para buscar el ID del trabajador por la cédula (utilizando consulta preparada para evitar inyección SQL)
    $stmtTrabajador = $conn->prepare("SELECT id_trabajador FROM trabajadores WHERE cedula = ?");
    $stmtTrabajador->bind_param("s", $cedula);
    $stmtTrabajador->execute();
    $resultTrabajador = $stmtTrabajador->get_result();

    if ($resultTrabajador->num_rows > 0) {
        $rowTrabajador = $resultTrabajador->fetch_assoc();
        $idTrabajador = $rowTrabajador['id_trabajador'];
    } else {
        // Manejar el caso en el que el trabajador no exista en la base de datos
        header("Location: ../error.html");
        exit();
    }

    // Función para buscar el ID del producto por el código (utilizando consulta preparada)
    $stmtProducto = $conn->prepare("SELECT id_producto FROM productos WHERE id_producto = ?");
    $stmtProducto->bind_param("s", $codigoProducto);
    $stmtProducto->execute();
    $resultProducto = $stmtProducto->get_result();

    if ($resultProducto->num_rows > 0) {
        $rowProducto = $resultProducto->fetch_assoc();
        $idProducto = $rowProducto['id_producto'];
    } else {
        // Manejar el caso en el que el producto no exista en la base de datos
        header("Location: ../dontexit.html");
        exit();
    }

    // Verificar si ya existe un registro para ese trabajador y producto en la tabla rendimiento
    $stmtCheck = $conn->prepare("SELECT * FROM rendimiento WHERE id_trabajador = ? AND id_producto = ?");
    $stmtCheck->bind_param("ss", $idTrabajador, $idProducto);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    $conn->query("SELECT @@global.time_zone"); // Cambia 'America/New_York' a tu zona horaria deseada

    // Preparar la consulta de inserción y multiplicar los inserts
    $stmtInsert = $conn->prepare("INSERT INTO rendimiento (cantidad_vendida, fecha_registro, id_producto, id_trabajador, hora_registro) VALUES (1, NOW(), ?, ?, NOW())");

    for ($i = 0; $i < $cantidadVendida; $i++) {
        $stmtInsert->bind_param("ss", $idProducto, $idTrabajador);
        $stmtInsert->execute();
    }

    header("Location: ../rendimientos.html"); // Redirige a rendimientos.html si la inserción es exitosa
    exit();
}

// Cerrar conexión
$conn->close();
?>
