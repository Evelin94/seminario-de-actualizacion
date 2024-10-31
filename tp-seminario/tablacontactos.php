<?php

include('env.php');
include('conn.php');


header('Content-Type: text/plain; charset=utf-8');
$json = file_get_contents('php://input');
$data = json_decode($json, true);


$sql = "SELECT * FROM Contactos ORDER BY Nombre, Apellido ASC";
$result = $conn->query($sql);

// SI HAY RESULTADOS CREAR ARRAY
if ($result->num_rows > 0) {
    $contactos = array();
    while ($row = $result->fetch_assoc()) {
        
        // BUSCAR TELEFONOS DEL CONTACTO
        $sql_telefonos = "SELECT NumeroTelefono FROM telefonos WHERE Contactos_idContactos = " . $row['idContactos'];
        $result_telefonos = $conn->query($sql_telefonos);    
        $telefonos = array();
        while ($row_telefono = $result_telefonos->fetch_assoc()) {
            $telefonos[] = $row_telefono['NumeroTelefono'];
        }
        
        $row['NumeroTelefono'] = $telefonos;
        $contactos[] = $row;       
    }

    // CONVERTIR AL FORMATO JSON 
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($contactos);
} else {
    echo "sin resultadoss";
}
$conn->close();

?>

