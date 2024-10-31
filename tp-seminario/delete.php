<?php

include('env.php');
include('conn.php');

// Leer el contenido JSON de la solicitud
$json = file_get_contents('php://input');

// Decodificar el JSON en un array asociativo
$data = json_decode($json, true);

// Recuperar el valor del parámetro 'numero'
$id = $data['numero'];

// RESPUESTA JSON
$response = array(
    'success' => false,
    'message' => ''
);

// BORRAR DATOS EN TABLA TELEFONOS
$sql = "DELETE FROM telefonos WHERE Contactos_idContactos='$id' ";
if ($conn->query($sql) === TRUE) {

// BORRAR DATOS EN TABLA CONTACTOS
$sql1 = "DELETE FROM contactos WHERE idContactos='$id' ";

if ($conn->query($sql1) === TRUE) {
        $response['success'] = true;
        $response['message'] = 'Datos borrados correctamente.';

} else {
        $response['message'] = 'Error al borrar datos del contacto: ' . $conn->error;
    }
} else {
    $response['message'] = 'Error al borrar telefonos del contacto: ' . $conn->error;
}

// Convertir respuesta a JSON y enviarla
echo json_encode($response);

// Cerrar conexión
$conn->close();

