<?php
session_start();
include('./conn.php'); 

// Función para obtener todos los headers
function getHeaders()
{
    $headers = [];
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
        }
    }
    return $headers;
}

// Obtener los headers
$headers = getHeaders();

// Recuperar los headers específicos
$hidUsuario = isset($headers['X-Idusuario']) ? $headers['X-Idusuario'] : null;
$husuario = isset($headers['X-Usuario']) ? $headers['X-Usuario'] : null;
$hpassword = isset($headers['X-Password']) ? $headers['X-Password'] : null;
$hoperacion = isset($headers['X-Operacion']) ? $headers['X-Operacion'] : null;

// RECUPERAR LAS VARIABLES DE SESION PARA EVITAR LA CONSULTA A LA DB
$sesionIdUsuario = isset($_SESSION['idusuario']) ? $_SESSION['idusuario'] : null;
$sesionUsuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
$sesionPassword = isset($_SESSION['password']) ? $_SESSION['password'] : null;

try {
    // Verificar si usuario y contraseña coinciden
    if ($sesionUsuario === $husuario && $sesionPassword === $hpassword) {


        // EL USUARIO ESTA VALIDADO , VERIFICAR PERMISO
       // echo json_encode(array('success' => true, 'message' => 'El Usuario esta verificado.'));

        // Establece la conexión usando PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Establece el modo de errores PDO en excepción
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparar y ejecutar la consulta para obtener el grupo del usuario
        $stmt = $conn->prepare("SELECT Grupos_idGrupos FROM puestos WHERE Usuarios_idUsuarios = :user_id");
        $stmt->bindParam(':user_id', $sesionIdUsuario);
        $stmt->execute();

        // Obtener los grupos del usuario
        $grupos = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Verificar si en alguno de los grupos a los que pertenece el usuario
        // está autorizado para ejecutar la acción

        $usuarioAutorizado = false;
        foreach ($grupos as $grupo) {
            // Verificar permisos basados en el grupo
            $stmt = $conn->prepare("SELECT * FROM permisos WHERE Grupos_idGrupos = :grupo_id AND Acciones_idAcciones = :operation");
            $stmt->bindParam(':grupo_id', $grupo);
            $stmt->bindParam(':operation', $hoperacion);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $usuarioAutorizado = true;
                echo json_encode(array('success' => true, 'message' => 'Esta autorizado para esta accion.'));
                break; // El usuario tiene permiso en al menos uno de los grupos, podemos salir del bucle
            }
        }

        if (!$usuarioAutorizado) {
            // Enviar respuesta JSON de error y detener la ejecución
            echo json_encode(array('success' => false, 'message' => 'No está autorizado para esta acción.'));
            exit; // Detener la ejecución del script
        }










    } else {
        // Enviar respuesta JSON de error y detener la ejecución
        echo json_encode(array('error' => 'Cliente o contraseña incorrectos'));
        exit; // Detener la ejecución del script
    }
} catch (PDOException $pdoe) {
    // En caso de error de conexión PDO, muestra el mensaje de error
    echo json_encode(array('error' => 'Error de conexión: ' . $pdoe->getMessage()));
    exit; // Detener la ejecución del script en caso de error de conexión
} catch (Exception $e) {
    // En caso de otros errores, muestra el mensaje de error
    echo json_encode(array('error' => 'Error: ' . $e->getMessage()));
    exit; // Detener la ejecución del script en caso de otros errores
}
