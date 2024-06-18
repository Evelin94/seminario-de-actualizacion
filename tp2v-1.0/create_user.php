<?php
session_start(); 

include_once('./database.php');

function validatePassword($password) {
 
    if (strlen($password) < 8 || strlen($password) > 20) {
        return false;
    }

    return true;
}


if (!$connection) {
    $status = array('success' => false, 'message' => 'coneccion a db no establecida');
    echo json_encode($status);
    die();
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);



$operation = $data['operation'];

switch ($operation) {
    case 'login':
        if (isset($data['username']) && isset($data['password'])) {
            $username = $data['username'];
            $password = $data['password'];

            try {
                $stmt = $connection->prepare("SELECT password FROM `user` WHERE name = :username");
                $stmt->bindParam(':username', $username);
                $stmt->execute();

                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    
                    $stmtSession = $connection->prepare("INSERT INTO `session-user` (estado, user_id) VALUES (1, :user_id)");
                    $stmtSession->bindParam(':user_id', $user['id']);
                    $stmtSession->execute();

                    $status = array('success' => true, 'message' => 'Login exitoso');
                    $status['profile_image'] = $user['profile_image'];
                } else {
                    $status = array('success' => false, 'message' => 'Credenciales incorrectas');
                }
            } catch (PDOException $e) {
                $status = array('success' => false, 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage());
            }
        } else {
            $status = array('success' => false, 'message' => 'Datos incompletos para login');
        }
        break;

    case 'signup':
        if (isset($data['username']) && isset($data['email']) && isset($data['password'])) {
            $username = $data['username'];
            $email = $data['email'];
            $password = $data['password'];

            if (!validatePassword($password)) {
                $status = array('success' => false, 'message' => 'La contraseña no cumple con los requisitos mínimos.');
                echo json_encode($status);
                exit;
            }
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            try {
                $stmt = $connection->prepare("INSERT INTO `user` (name, email, password) VALUES (:username, :email, :password)");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashedPassword);

                if ($stmt->execute()) {
                    $status = array('success' => true, 'message' => 'Usuario creado satisfactoriamente! (perfectirijillo)');
                } else {
                    $status = array('success' => false, 'message' => 'Error al crear el usuario: ' . implode(":", $stmt->errorInfo()));
                }
            } catch (PDOException $e) {
                $status = array('success' => false, 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage());
            }
        } else {
            $status = array('success' => false, 'message' => 'Datos incompletos para el registro');
        }
        break;
        case 'checkSession':
            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];
    
                try {
                    $stmt = $connection->prepare("SELECT estado FROM `session-user` WHERE user_id = :user_id");
                    $stmt->bindParam(':user_id', $userId);
                    $stmt->execute();
                    $session = $stmt->fetch(PDO::FETCH_ASSOC);
    
                    if ($session && $session['estado'] == 1) {
                        $status = array('success' => true, 'message' => 'El usuario tiene la sesión iniciada');
                    } else {
                        $status = array('success' => false, 'message' => 'El usuario no tiene la sesión iniciada');
                    }
                } catch (PDOException $e) {
                    $status = array('success' => false, 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage());
                }
            } else {
                $status = array('success' => false, 'message' => 'El usuario no tiene la sesión iniciada');
            }
            break;

    default:
        $status = array('success' => false, 'message' => 'Operación no válida');
}

echo json_encode($status);
?>
