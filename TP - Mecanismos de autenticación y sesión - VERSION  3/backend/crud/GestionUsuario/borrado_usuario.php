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

// Verifica si el token está presente
 
    if ($autenticacion) {
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

            if (isset($data['idusuario'])) {
                $idusuario = $data['idusuario'];

                // Verificar si el usuario existe
                $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE idUsuarios = :idusuario");
                $checkStmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
                $checkStmt->execute();
                $userExists = $checkStmt->fetchColumn();

                if ($userExists) {
                    // Preparar y ejecutar la consulta SQL para borrar
                    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE idUsuarios = :idusuario");
                    $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);

                    if ($stmt->execute()) {

                    if ($_SESSION['authMethod'] == 'token') {
                        echo json_encode(array('success' => true, 'token' => $newtoken, 'message' => 'Usuario borrado exitosamente'));
                    }

                    if ($_SESSION['authMethod'] == 'userPass') {
                        echo json_encode(array('success' => true, 'message' => 'Usuario borrado exitosamente'));
                    }           


                    } else {
                        echo json_encode([
                            'success' => false,
                            'message' => "Error al borrar usuario"
                        ]);
                    }
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => "El ID de usuario no existe"
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "ID de usuario no proporcionado"
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
