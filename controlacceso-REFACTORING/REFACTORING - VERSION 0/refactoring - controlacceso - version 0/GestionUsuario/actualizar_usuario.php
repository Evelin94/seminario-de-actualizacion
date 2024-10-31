<?php
include('../conn.php');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
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
            echo json_encode(["success" => "Usuario actualizado correctamente"]);
        } else {
            echo json_encode(["error" => "Error al actualizar usuario"]);
        }
    } else {
        echo json_encode(["error" => "Datos Invalidos"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Error en la conexion: " . $e->getMessage()]);
}

$conn = null;
