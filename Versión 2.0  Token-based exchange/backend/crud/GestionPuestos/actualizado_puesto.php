<?php
session_start();
include('../conn.php');

// VARIABLE DE AUTENTICACIÓN
$autenticacion = false;

if ($_SESSION['authMethod'] == 'token') {
    include('../verificarSesion.php');
    include('../generarNewToken.php');
    $autenticacion = validateSessionToken($htoken);
    $newtoken = generateSessionToken();
    $_SESSION['session_token'] = $newtoken;
}

if ($_SESSION['authMethod'] == 'userPass') {
    include('../verificarSesionUser.php');
    $autenticacion = validateSession($husuario, $hpassword);
}

if ($autenticacion) {
// Recibir los datos JSON
$data = json_decode(file_get_contents("php://input"));

// Verificar si se recibieron los datos esperados
if (isset($data->userId) && isset($data->groupId) && isset($data->positionId)) {
    try {
        // Crear conexión usando PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        // Establecer el modo de error de PDO a excepción
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparar y ejecutar la consulta de actualización
        $stmt = $conn->prepare("UPDATE puestos SET Usuarios_idUsuarios = :userId, Grupos_idGrupos = :groupId WHERE idPuestos = :positionId");
        $stmt->bindParam(':userId', $data->userId);
        $stmt->bindParam(':groupId', $data->groupId);
        $stmt->bindParam(':positionId', $data->positionId);
        $stmt->execute();

            if ($_SESSION['authMethod'] == 'token') {
                echo json_encode(array('success' => true, 'token' => $newtoken, 'message' => 'Puesto actualizado exitosamente'));
            }

            if (
                $_SESSION['authMethod'] == 'userPass'
            ) {
                echo json_encode(array('success' => true, 'message' => 'Puesto actualizado exitosamente'));
            }       


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
    echo json_encode(['error' => 'Token de sesión no valido']);
}
