<?php
include('../conn.php');

try {
    // Establece la conexión usando PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Establece el modo de errores PDO en excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Realiza la consulta a la base de datos
    $stmt = $conn->prepare("SELECT puestos.idPuestos, usuarios.Usuario, grupos.Grupo 
                            FROM puestos 
                            INNER JOIN usuarios ON puestos.Usuarios_idUsuarios = usuarios.idUsuarios
                            INNER JOIN grupos ON puestos.Grupos_idGrupos = grupos.idGrupos");
    $stmt->execute();

    // Obtiene los resultados y los convierte a formato JSON
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
} catch (PDOException $e) {
    // En caso de error, muestra el mensaje de error
    echo "Error: " . $e->getMessage();
}

// Cierra la conexión
$conn = null;
