<?php
include('../conn.php');

// Respuesta por defecto
$response = array(
    'success' => false,
    'message' => 'No se recibieron datos de grupo y accion.'
);

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
            // Actualiza la respuesta en caso de éxito
            $response['success'] = true;
            $response['message'] = "Los datos se insertaron correctamente.";
        } else {
            $response['message'] = "Error al insertar datos.";
        }
    } catch (PDOException $e) {
        $response['message'] = "Error de conexión: " . $e->getMessage();
    }

    // Cierra la conexión
    $conn = null;
}

// Envía la respuesta JSON
echo json_encode($response);
?>
