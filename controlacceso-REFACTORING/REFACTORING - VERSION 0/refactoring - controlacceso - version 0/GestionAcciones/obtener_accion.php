<?php
include('../conn.php');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['actionId'])) {
        $actionId = $data['actionId'];
        $stmt = $conn->prepare("SELECT * FROM acciones WHERE idAcciones = :id");
        $stmt->bindParam(':id', $actionId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            echo json_encode(["error" => "No se encontro la Accion"]);
        }
    } else {
        echo json_encode(["error" => "Id invalido"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Fallo en la conexion: " . $e->getMessage()]);
}

$conn = null;
