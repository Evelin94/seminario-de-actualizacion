<?php
session_start();
include('../conn.php');
include('../verificarSesion.php');
include('../generarNewToken.php');

if (validateSessionToken($htoken)) {
// Respuesta por defecto
$response = array(
    'success' => false,
    'message' => 'No se recibieron datos de usuario y grupo.'
);

// Recibe los datos enviados por la solicitud JSON
$data = json_decode(file_get_contents("php://input"));

// Verifica si los datos se recibieron correctamente
if (isset($data->usuario) && isset($data->grupo)) {    
    try {
        // Crea una nueva conexión PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        // Habilita el modo de errores PDO
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepara la consulta SQL para insertar datos en la tabla puestos
        $sql = "INSERT INTO puestos (Usuarios_idUsuarios, Grupos_idGrupos) VALUES (:usuario, :grupo)";
        $stmt = $conn->prepare($sql);

        // Vincula los parámetros y ejecuta la consulta
        $stmt->bindParam(':usuario', $data->usuario);
        $stmt->bindParam(':grupo', $data->grupo);

        if ($stmt->execute()) {


                // Actualiza la respuesta en caso de éxito
                // Generate a new token
                $newtoken = generateSessionToken();
                $_SESSION['session_token'] = $newtoken;

                echo json_encode(array('success' => true, 'token' => $newtoken, 'message' => 'Los datos se insertaron correctamente.'));

        } else {         
            echo json_encode(array('success' => false, 'message' => 'Error al insertar datos.'));
        }
    } catch (PDOException $e) {
        echo json_encode(array('success' => false, 'message' => 'Error de conexion: ' . $e->getMessage()));
    }

    // Cierra la conexión
    $conn = null;
} else {
        echo json_encode(['error' => 'Token de sesión no válido']);
    }


}

?>
