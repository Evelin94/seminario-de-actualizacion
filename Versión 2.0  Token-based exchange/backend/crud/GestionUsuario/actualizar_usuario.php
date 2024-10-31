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
            $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data['userId']) && isset($data['name']) && isset($data['password'])) {
                $userId = $data['userId'];
                $name = $data['name'];
                $password = $data['password'];

                $stmt = $conn->prepare("UPDATE usuarios SET usuario = :name, password = :password WHERE idUsuarios = :id");
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);

                if ($stmt->execute()) {

                if ($_SESSION['authMethod'] == 'token') {
                    echo json_encode(array('success' => true, 'token' => $newtoken, 'message' => 'Usuario registrado exitosamente'));
                }

                if ($_SESSION['authMethod'] == 'userPass'
                ) {
                    echo json_encode(array('success' => true, 'message' => 'Usuario registrado exitosamente'));
                }


                } else {
                    echo json_encode(["error" => "Error al actualizar usuario"]);
                }
            } else {
                echo json_encode(["error" => "Datos inválidos"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error en la conexión: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Token de sesión no válido']);
    }

