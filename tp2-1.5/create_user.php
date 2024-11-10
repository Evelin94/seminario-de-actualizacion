<?php

error_reporting(0);
ini_set('display_errors', 0);
include_once('./database.php');

session_start();
header('Content-Type: application/json');

function validatePassword($password) {
    return strlen($password) >= 8 && strlen($password) <= 20;
}


if (!$connection) {
    echo json_encode(array('success' => false, 'message' => 'Conexión a DB no establecida'));
    exit;
}


$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (isset($data['username'], $data['email'], $data['password'])) {
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];

    if (!validatePassword($password)) {
        echo json_encode(array('success' => false, 'message' => 'La contraseña no cumple con los requisitos mínimos.'));
        exit;
    }

   
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    try {
    
        $stmt = $connection->prepare("INSERT INTO user (name, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);

        if ($stmt->execute()) {
            echo json_encode(array('success' => true, 'message' => 'Usuario creado satisfactoriamente!'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error al crear el usuario: ' . implode(":", $stmt->errorInfo())));
        }
    } catch (PDOException $e) {
        echo json_encode(array('success' => false, 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage()));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Datos incompletos para el registro'));
}
?>
