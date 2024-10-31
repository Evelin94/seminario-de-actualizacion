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
            // Crear una nueva conexión PDO
            $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Obtener el cuerpo de la solicitud JSON
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data['userId'])) {
                $userId = $data['userId'];
                $stmt = $conn->prepare("SELECT * FROM usuarios WHERE idUsuarios = :id");
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {

                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    // Responder con datos y nuevo token    

                if ($_SESSION['authMethod'] == 'token') {
                    echo json_encode(array('success' => true, 'data' => $result, 'token' => $newtoken, 'message' => 'Usuario registrado exitosamente'));
                }

                if ($_SESSION['authMethod'] == 'userPass') {
                    echo json_encode(array('success' => true, 'data' => $result, 'message' => 'Usuario registrado exitosamente'));
                }
                
                } else {
                    echo json_encode(["error" => "No user found"]);
                }
            } else {
                echo json_encode(["error" => "Invalid ID"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Connection failed: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Invalid session token']);
    }
