<?php
include 'db.php'; // Incluir el archivo de conexión a la base de datos

if (isset($_POST['product-name'], $_POST['product-rendimiento-hora'])) {
    $productName = $_POST['product-name'];
    $productRendimientoHora = $_POST['product-rendimiento-hora'];

    // Validar y limpiar los datos (usar consultas preparadas es más seguro)
    $productName = $conn->real_escape_string($productName);
    $productRendimientoHora = $conn->real_escape_string($productRendimientoHora);

    // Sentencia preparada para la inserción
    $stmt = $conn->prepare("INSERT INTO productos (nombre_producto, rendimiento_producto_hora) VALUES (?, ?)");
    $stmt->bind_param("ss", $productName, $productRendimientoHora);
    // 'ss' significa dos strings, ajusta los tipos de datos según sea necesario

    if ($stmt->execute()) {
        echo 'Se ha ingresado correctamente';
        header("Location: ../ingProductos1.html"); // Redirigir con mensaje de éxito
        exit();
    } else {
        echo 'Hubo un error al subir el Producto';
        header("Location: ../index.html" . urlencode($conn->error)); // Redirigir con mensaje de error
        exit();
    }

    $stmt->close(); // Cerrar la sentencia preparada
}

$conn->close(); // Cerrar la conexión a la base de datos
?>


