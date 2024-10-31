<?php
include('../conn.php');

try {
    // Crear una nueva conexión PDO
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);

    // Obtener el cuerpo de la solicitud JSON
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (isset($data['idpuesto'])) {
        $idpuesto = $data['idpuesto'];

        // Verificar si el puesto existe
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM puestos WHERE idPuestos = :idpuesto");
        $checkStmt->bindParam(':idpuesto', $idpuesto, PDO::PARAM_INT);
        $checkStmt->execute();
        $userExists = $checkStmt->fetchColumn();

        if ($userExists) {
            // Preparar y ejecutar la consulta SQL para borrar
            $stmt = $pdo->prepare("DELETE FROM puestos WHERE idPuestos = :idpuesto");
            $stmt->bindParam(':idpuesto', $idpuesto, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => "Puesto borrado exitosamente"
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "Error al borrar puesto"
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => "El ID de puesto no existe"
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => "ID de puesto no proporcionado"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => "Error en la conexión: " . $e->getMessage()
    ]);
}
