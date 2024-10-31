<?php
session_start();
include('../conn.php');
include('../verificarSesion.php');
include('../generarNewToken.php');

if (validateSessionToken($htoken)) {
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

    if (isset($data['idpermiso'])) {
        $idpermiso = $data['idpermiso'];

        // Verificar si el permiso existe
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM permisos WHERE idPermisos = :idpermiso");
        $checkStmt->bindParam(':idpermiso', $idpermiso, PDO::PARAM_INT);
        $checkStmt->execute();
        $userExists = $checkStmt->fetchColumn();

        if ($userExists) {
            // Preparar y ejecutar la consulta SQL para borrar
            $stmt = $pdo->prepare("DELETE FROM permisos WHERE idPermisos = :idpermiso");
            $stmt->bindParam(':idpermiso', $idpermiso, PDO::PARAM_INT);

            if ($stmt->execute()) {

                    // Generar un nuevo token
                    $newtoken = generateSessionToken();
                    $_SESSION['session_token'] = $newtoken;

                    echo json_encode([
                        'success' => true,
                        'token' => $newtoken,
                        'message' => "Permiso borrado exitosamente"
                    ]);
              
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "Error al borrar permiso"
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => "El ID de permiso no existe"
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => "ID de permiso no proporcionado"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => "Error en la conexión: " . $e->getMessage()
    ]);
}
} else {
    echo json_encode(['error' => 'Token de sesión no válido']);
}