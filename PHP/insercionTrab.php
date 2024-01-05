<?php
include 'db.php';

// Capturar datos del formulario
if (isset($_POST['nombre'])) {
    $nombre = $_POST['nombre'];
} else {
    $nombre ='';
}
if (isset($_POST['apellido'])) {
    $apellido = $_POST['apellido'];
} else {
    $apellido ='';
}
if (isset($_POST['cedula'])) {
    $cedula = $_POST['cedula'];
} else {
    $cedula ='';
}

 // Verificar si la cédula ya existe en la base de datos
 $sql_check = "SELECT * FROM trabajadores WHERE cedula = '$cedula'";
 $result_check = $conn->query($sql_check);

 if ($result_check && $result_check->num_rows > 0) {
     // Si la cédula ya existe, actualizar los datos del trabajador
     $sql_update = "UPDATE trabajadores SET nombre = '$nombre', apellido = '$apellido' WHERE cedula = '$cedula'";

     if ($conn->query($sql_update) === TRUE) {
         header("Location: ../ingPersonal.html"); // Redirige a ingPersonal.html si la actualización es exitosa
         exit();
     } else {
         header("Location: ../index.html"); // Redirige a index.html si hay un error en la actualización
         exit();
     }
 } else {
     // Si la cédula no existe, realizar la inserción
     $sql_insert = "INSERT INTO trabajadores (nombre, apellido, cedula) VALUES ('$nombre', '$apellido', '$cedula')";

     if ($conn->query($sql_insert) === TRUE) {
         header("Location: ../ingPersonal.html"); // Redirige a ingPersonal.html si la inserción es exitosa
         exit();
     } else {
         header("Location: ../index.html"); // Redirige a index.html si hay un error en la inserción
         exit();
     }
 }

// Cerrar conexión
$conn->close();
?>