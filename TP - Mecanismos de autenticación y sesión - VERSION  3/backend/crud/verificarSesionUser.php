<?php
// VALIDAR TOKEN

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

$husuario = isset($headers['X-Usuario']) ? $headers['X-Usuario'] : null;
$hpassword = isset($headers['X-Password']) ? $headers['X-Password'] : null;
// Verificar el token de sesión en cada solicitud
function validateSession($husuario,$hpassword)
{
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['password'])) {
        return false; // No hay usuario o contraseña en la sesión
    }

    $storedUser = $_SESSION['usuario'];
    $storedPass = $_SESSION['password'];

    return $storedUser === $husuario && $storedPass === $hpassword;
}




