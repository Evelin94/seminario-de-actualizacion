<?php

// Función para generar una cadena alfanumérica aleatoria de 16 caracteres
function generateRandomString($length = 16) {
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < $length; $i++) { $randomString .=$characters[random_int(0, $charactersLength - 1)]; } return $randomString; }

// Función para generar un token de sesión seguro a partir de una cadena alfanumérica
function generateSessionToken()
{
    $randomString = generateRandomString();
    return hash('sha256', $randomString); // Aplica SHA-256 directamente a la cadena aleatoria
}

// Verificar el token de sesión en cada solicitud
function validateSessionToken($receivedToken)
{
    if (!isset($_SESSION['session_token'])) {
        return false; // No hay token en la sesión
    }

    $storedToken = $_SESSION['session_token'];
    return hash_equals($storedToken, $receivedToken);
}
