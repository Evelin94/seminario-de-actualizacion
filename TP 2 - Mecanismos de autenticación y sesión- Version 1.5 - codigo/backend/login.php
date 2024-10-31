<?php
include('conn.php');
session_start();

// Verificar que la solicitud sea mediante el método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Obtener los datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"));

    // Validar que se recibieron los datos esperados
    if (isset($data->usuario) && isset($data->contraseña)) {

        $usuario = trim($data->usuario);
        $contraseña = trim($data->contraseña);

        // Preparar y ejecutar la consulta SQL
        $sql = "SELECT * FROM usuarios WHERE usuario = :usuario AND password = :contrasena";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':contrasena', $contraseña);
        $stmt->execute();

        // Obtener el usuario de la base de datos
        $user = $stmt->fetch();

        if ($user) {
            // Login exitoso, configurar la sesión
            $_SESSION['idusuario'] = $user['idUsuarios'];
            $_SESSION['usuario'] = $usuario;
            $_SESSION['password'] = $contraseña;
            

            //Insertar en la tabla sesiones
            $sqlInsert = "INSERT INTO sesiones (idUsuario, inicioSesion) VALUES (:usuario_id, NOW())";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->bindParam(':usuario_id', $user['idUsuarios']);
            $stmtInsert->execute();

            // Obtener el idSesion del registro insertado
            $idSesion = $pdo->lastInsertId();
            $_SESSION['idsesion'] = $idSesion;

            // Enviar respuesta JSON de éxito
            echo json_encode(array(
                "success" => true,
                "idUsuario" => $user['idUsuarios'],
                "usuario" => $user['usuario'],
                "contraseña" => $user['password']
            ));
            exit;
        } else {
            // Usuario o contraseña incorrectos
            echo json_encode(array("message" => "Usuario o contraseña incorrectos"));
        }
    } else {
        // Faltan datos en la solicitud
        echo json_encode(array("message" => "Faltan datos de usuario o contraseña"));
    }
} else {
    // Solicitud no válida
    echo json_encode(array("message" => "Solicitud no válida"));
}
