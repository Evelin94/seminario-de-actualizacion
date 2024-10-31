<?php
include('../conn.php');

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
            echo json_encode(["success" => "Accion actualizada correctamente"]);
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
?>