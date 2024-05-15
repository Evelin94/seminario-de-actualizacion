<?php

$servername = "localhost"; 
$username = "root"; 
//accedo a la contraseña de la variable de retorno guardada
$password = getenv("MYSQL_PASSWORD"); 
$database = "MySQL";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];

    $sql = "INSERT INTO Contacto (Nombre, Telefono) VALUES ('$nombre', '$telefono')";
    if ($conn->query($sql) === TRUE) {
        echo "¡Nuevo contacto agregado exitosamente!";
    } else {
        echo "Error al agregar el contacto: " . $conn->error;
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    $sql = "SELECT * FROM Contacto";
    $result = $conn->query($sql);
    $contactos = array();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $contacto = array(
                'nombre' => $row['Nombre'],
                'telefono' => $row['Telefono']
            );
            array_push($contactos, $contacto);
        }
    }
    echo json_encode($contactos);
}

$conn->close();
?>

