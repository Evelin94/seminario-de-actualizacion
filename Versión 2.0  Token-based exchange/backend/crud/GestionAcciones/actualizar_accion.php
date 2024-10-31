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
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['actionId']) && isset($data['accion'])) {
        $actionId = $data['actionId'];
        $action = $data['accion'];

        $stmt = $conn->prepare("UPDATE acciones SET Accion = :action WHERE idAcciones = :id");
        $stmt->bindParam(':id', $actionId, PDO::PARAM_INT);
        $stmt->bindParam(':action', $action, PDO::PARAM_STR);

        if ($stmt->execute()) {

                if ($_SESSION['authMethod'] == 'token') {
                    echo json_encode(array('success' => true, 'token' => $newtoken, 'message' => 'Accion actualizada exitosamente'));
                }

                if ($_SESSION['authMethod'] == 'userPass') {
                    echo json_encode(array('success' => true, 'message' => 'Accion actualizada exitosamente'));
                }
      

        } else {
            echo json_encode(["error" => "Error al actualizar accion"]);
        }
    } else {
        echo json_encode(["error" => "Datos inválidos"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Error en la conexión: " . $e->getMessage()]);
}

$conn = null;
} else {
    echo json_encode(['error' => 'Token de sesión no proporcionado']);
}


?>