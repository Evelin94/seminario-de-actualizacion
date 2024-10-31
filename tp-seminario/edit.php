<?php

include('env.php');
include('conn.php');

// RECIBIR JSON
$json = file_get_contents('php://input');

// PASAR JSON A UN ARRAY ASOCIATIVO
$data = json_decode($json, true);

// ACCEDER A LOS DATOS  
$id= $data['idcontactos'];
$nombre = $data['nombre'];
$apellido = $data['apellido'];
$email = $data['email'];
$telefono[0] = $data['telefono1'];
$telefono[1] = $data['telefono2'];
$telefono[2] = $data['telefono3'];

// TELEFONOS ACTUALIZADOS
$datos=array_filter($telefono);
$numupdated=count($datos,0);

// RESPUESTA JSON
$response = array(
    'success' => false,
    'message' => ''
);

// ACTUALIZAR DATOS EN TABLA CONTACTOS
$sql = "UPDATE contactos 
        SET Nombre = '$nombre', Apellido = '$apellido', Email = '$email'
        WHERE idContactos='$id' ";

if ($conn->query($sql) === TRUE) {

    // TELEFONOS ACTUALES DEL CONTACTO
    $sql1 = "SELECT * FROM telefonos WHERE Contactos_idContactos= '$id'";
    $resultado=$conn->query($sql1);    
    $actuales=mysqli_num_rows($resultado);
    
    // ACTUALIZAR DATOS EXISTENTES
    $contador=0;
        while ($fila = $resultado->fetch_assoc()) {
        $index = $fila['idTelefono'];
        $sql2 = "UPDATE telefonos 
        SET NumeroTelefono = '$telefono[$contador]'
        WHERE idTelefono= '$index' ";
        $conn->query($sql2);
        $contador++;
        }
     
    while($numupdated>$contador)
    {
        // AGREGAR DATOS ACTUALIZADOS QUE SUPERAN LOS DATOS EXISTENTES
        $sql3 = "INSERT INTO telefonos (NumeroTelefono , Contactos_idContactos) VALUES ( '$telefono[$contador]', '$id' )";
        $conn->query($sql3);  
        $contador++;  
    }

    $response['success'] = true;
    $response['message'] = 'Datos actualizados correctamente.';
} else {
    $response['message'] = 'Error al actualizar datos: ' . $conn->error;
}

// Convertir respuesta a JSON y enviarla
echo json_encode($response);

// Cerrar conexión
$conn->close();





    
   



