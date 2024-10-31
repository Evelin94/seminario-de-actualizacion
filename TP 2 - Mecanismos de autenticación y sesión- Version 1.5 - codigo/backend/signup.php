<?php

include('conn.php');

// Función para validar la contraseña con expresiones regulares
function validarContraseña($contraseña)
{
    // Expresiones regulares y descripciones de las condiciones
    $condicionesContraseña = [
        '/^[A-Za-z]/' => 'Debe empezar con una letra',
        '/^.{8,16}$/' => 'Debe tener entre 8 y 16 caracteres',
        '/[A-Z]/' => 'Debe contener al menos una mayúscula',
        '/\d/' => 'Debe contener al menos un número',
        '/[!@#$%^&*(),.?":{}|<>]/' => 'Debe contener al menos un carácter especial'
    ];

    $condicionesNoCumplidasContraseña = [];

    foreach ($condicionesContraseña as $regex => $descripcion) {
        if (!preg_match($regex, $contraseña)) {
            $condicionesNoCumplidasContraseña[] = $descripcion;
        }
    }

    return $condicionesNoCumplidasContraseña;
}

// Función para validar el usuario con expresiones regulares
function validarUsuario($usuario)
{
    $condicionesUsuario = [
        '/^[A-Za-z]/' => 'Debe empezar con una letra',
        '/^.{6,16}$/' => 'Debe tener entre 6 y 16 caracteres',
        '/[A-Z]/' => 'Debe contener al menos una mayúscula',
    ];

    $condicionesNoCumplidasUsuario = [];

    foreach ($condicionesUsuario as $regex => $descripcion) {
        if (!preg_match($regex, $usuario)) {
            $condicionesNoCumplidasUsuario[] = $descripcion;
        }
    }

    return $condicionesNoCumplidasUsuario;
}


// Verificar que la solicitud sea mediante el método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Obtener los datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"));

    // Validar que se recibieron los datos esperados
    if (isset($data->usuario) && isset($data->contraseña)) {

        // Validar la seguridad de los datos ingresados
        $usuario = $data->usuario;
        $contraseña = $data->contraseña;

        // Validar contraseña usando la función
        $erroresContraseña = validarContraseña($contraseña);

        if (!empty($erroresContraseña)) {
            // Hay errores en la contraseña, devolver mensaje de error
            http_response_code(400); // Bad Request
            echo json_encode(array("message" => "Error en la contraseña", "errors" => $erroresContraseña));
            exit;
        }

        // Validar contraseña usando la función
        $erroresUsuario = validarUsuario($usuario);

        if (!empty($erroresUsuario)) {
            // Hay errores en el usuario, devolver mensaje de error
            http_response_code(400); // Bad Request
            echo json_encode(array("message" => "Error en el usuario", "errors" => $erroresUsuario));
            exit;
        }


        // Hash de la contraseña
        $hashedPassword = password_hash($contraseña, PASSWORD_DEFAULT);

        // Consulta preparada para insertar en la base de datos
        $stmt = $pdo->prepare("INSERT INTO usuarios (Usuario, Password) VALUES (?, ?)");

        try {
            $stmt->execute([$usuario, $hashedPassword]);
            // Ejemplo de respuesta JSON exitosa
            echo json_encode(array("message" => "Datos ingresados correctamente"));
        } catch (PDOException $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(array("message" => "Error al ingresar datos: " . $e->getMessage()));
        }
    } else {
        // Error si no se recibieron los datos esperados
        http_response_code(400); // Bad Request
        echo json_encode(array("message" => "Error en los datos recibidos"));
    }
} else {
    // Error si la solicitud no es mediante POST
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("message" => "Método no permitido"));
}

