<?php
include('../conn.php');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['groupId']) && isset($data['grupo'])) {
        $groupId = $data['groupId'];
        $group = $data['grupo'];

        $stmt = $conn->prepare("UPDATE grupos SET Grupo = :group WHERE idGrupos = :id");
        $stmt->bindParam(':id', $groupId, PDO::PARAM_INT);
        $stmt->bindParam(':group', $group, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo json_encode(["success" => "Grupo actualizado correctamente"]);
        } else {
            echo json_encode(["error" => "Error al actualizar grupo"]);
        }
    } else {
        echo json_encode(["error" => "Datos inválidos"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Error en la conexión: " . $e->getMessage()]);
}

$conn = null;
?>