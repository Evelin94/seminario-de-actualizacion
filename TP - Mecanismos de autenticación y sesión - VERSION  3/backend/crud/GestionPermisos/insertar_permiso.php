<?php
session_start();
include('../conn.php');
include('../verificarSesion.php');
include('../generarNewToken.php');

if (validateSessionToken($htoken)) {



// Recibe los datos enviados por la solicitud JSON
$data = json_decode(file_get_contents("php://input"));

// Verifica si los datos se recibieron correctamente
if (isset($data->accion) && isset($data->grupo)) {    
    try {
        // Crea una nueva conexión PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        // Habilita el modo de errores PDO
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepara la consulta SQL para insertar datos en la tabla puestos
        $sql = "INSERT INTO permisos (Grupos_idGrupos, Acciones_idAcciones) VALUES (:grupo, :accion)";
        $stmt = $conn->prepare($sql);

        // Vincula los parámetros y ejecuta la consulta
        $stmt->bindParam(':grupo', $data->grupo);
        $stmt->bindParam(':accion', $data->accion);

        if ($stmt->execute()) {
                // Generate a new token
                $newtoken = generateSessionToken();
                $_SESSION['session_token'] = $newtoken;

                echo json_encode(array('success' => true, 'token' => $newtoken, 'message' => 'Permiso registrado exitosamente'));
          
        } else {
                echo json_encode(array('success' => false, 'message' => 'Error al registrar permiso'));
        }
    } catch (PDOException $e) {
            echo json_encode(array('success' => false, 'message' => 'Error: ' . $e->getMessage()));
    }

    // Cierra la conexión
    $conn = null;
}
} else {
    echo json_encode(['error' => 'Token de sesión no válido']);
}


?>
