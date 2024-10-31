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
    // Establece la conexión usando PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Establece el modo de errores PDO en excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Realiza la consulta a la base de datos
    $stmt = $conn->prepare("SELECT * FROM acciones");
    $stmt->execute();

        // Fetch the results
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Responder con los datos y el nuevo token si es método token
        $response = [
            'data' => $result,
        ];

        if ($_SESSION['authMethod'] == 'token') {
            $response['token'] = $newtoken;
        }

        echo json_encode($response);

} catch (PDOException $e) {
    // En caso de error, muestra el mensaje de error
    echo "Error: " . $e->getMessage();
}

// Cierra la conexión
$conn = null;
} else {
    echo json_encode(['error' => 'Token de sesión no válido']);
}
