<?php
// Establecer conexión con la base de datos
$servername = "localhost";
$username = "root"; 
$password = getenv("MYSQL_PASSWORD"); 
$database = "MySQL"; 

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Manejar la inserción de nuevos contactos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];

    // Preparar y ejecutar la consulta SQL para insertar el nuevo contacto
    $sql = "INSERT INTO Contacto (Nombre, Telefono) VALUES ('$nombre', '$telefono')";
    if ($conn->query($sql) === TRUE) {
        echo "¡Nuevo contacto agregado exitosamente!";
    } else {
        echo "Error al agregar el contacto: " . $conn->error;
    }
}

// Consultar la lista de contactos desde la base de datos
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

// Devolver los contactos en formato JSON
echo json_encode($contactos);

// Cerrar conexión
$conn->close();
?>
