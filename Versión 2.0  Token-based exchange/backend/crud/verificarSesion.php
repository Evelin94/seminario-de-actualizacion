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
$htoken = isset($headers['X-Token']) ? $headers['X-Token'] : null;

// Verificar el token de sesión en cada solicitud
function validateSessionToken($receivedToken)
{
    if (!isset($_SESSION['session_token'])) {
        return false; // No hay token en la sesión
    }

    $storedToken = $_SESSION['session_token'];
    return hash_equals($storedToken, $receivedToken);
}




