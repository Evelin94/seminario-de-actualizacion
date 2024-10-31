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

    if (isset($data['groupId'])) {
        $groupId = $data['groupId'];
        $stmt = $conn->prepare("SELECT * FROM grupos WHERE idGrupos = :id");
        $stmt->bindParam(':id', $groupId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            
             $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($_SESSION['authMethod'] == 'token') {
                    echo json_encode(array('success' => true, 'data' => $result , 'token' => $newtoken));
                }

                if ($_SESSION['authMethod'] == 'userPass') {
                    echo json_encode(array('success' => true, 'data' => $result));
                }
                              
        
        
        } else {
            echo json_encode(["error" => "No se encontro el Grupo"]);
        }
    } else {
        echo json_encode(["error" => "Id invalido"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Fallo en la conexion: " . $e->getMessage()]);
}

$conn = null;
} else {
    echo json_encode(['error' => 'Session token not provided']);
}