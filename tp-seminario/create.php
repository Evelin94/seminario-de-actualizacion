<?php
include('env.php');
include('conn.php');

// RECIBIR JSON 
$json = file_get_contents('php://input');

// PASAR JSON A UN ARRAY ASOCIATIVO
$data = json_decode($json, true);

// ACCEDER A LOS DATOS  
$nombre = $data['nombre'];
$apellido = $data['apellido'];
$email = $data['email'];
$telefono[0] = $data['telefono1'];
$telefono[1] = $data['telefono2'];
$telefono[2] = $data['telefono3'];

// RESPUESTA JSON
$response = array(
    'success' => false,
    'message' => ''
);

// INSERTAR DATOS EN TABLA CONTACTOS
$sql = "INSERT INTO contactos (Nombre, Apellido, Email) VALUES ('$nombre', '$apellido', '$email')";
if ($conn->query($sql) === TRUE) {
    $contacto_id = $conn->insert_id;
    for ($j = 0; $j <= 2; $j++) {
        if (!empty($telefono[$j])) {
            $sql_telefono = "INSERT INTO telefonos (NumeroTelefono,Contactos_idContactos) VALUES ('$telefono[$j]', '$contacto_id')";
            $conn->query($sql_telefono);
        }
    }
    $response['success'] = true;
    $response['message'] = 'Datos insertados correctamente.';
} else {
    $response['message'] = 'Error al insertar datos: ' . $conn->error;
}

// Convertir respuesta a JSON y enviarla
echo json_encode($response);

// Cerrar conexión
$conn->close();
