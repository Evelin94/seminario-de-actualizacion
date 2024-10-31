<?php
session_start();
include('../conn.php');
include('../verificarSesion.php');
include('../generarNewToken.php');

if (validateSessionToken($htoken)) {

// Recibir los datos JSON
$data = json_decode(file_get_contents("php://input"));

// Verificar si se recibieron los datos esperados
if ( isset($data->permisoId) && isset($data->groupId) && isset($data->actionId)) {
    try {
        // Crear conexión usando PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        // Establecer el modo de error de PDO a excepción
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparar y ejecutar la consulta de actualización
        $stmt = $conn->prepare("UPDATE permisos SET Grupos_idGrupos = :groupId , Acciones_idAcciones = :actionId   WHERE idPermisos = :permisoId");
        $stmt->bindParam(':actionId', $data->actionId);
        $stmt->bindParam(':groupId', $data->groupId);
        $stmt->bindParam(':permisoId', $data->permisoId);       

        $stmt->execute();
            // Generate a new token
            $newtoken = generateSessionToken();
            $_SESSION['session_token'] = $newtoken;

       
        echo json_encode(array('success' => true, 'token' => $newtoken, 'message' => 'Permiso modificado exitosamente'));

    } catch (PDOException $e) {
        // Devolver el error en formato JSON
        echo json_encode(["error" => "Error al actualizar la tabla de puestos: " . $e->getMessage()]);
    }
    // Cerrar conexión
    $conn = null;
} else {
    // Devolver un error en formato JSON si no se recibieron los datos esperados
    echo json_encode(["error" => "No se recibieron los datos esperados"]);
}
} else {
    echo json_encode(['error' => 'Token de sesión no válido']);
}



?>
