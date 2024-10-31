<?php
session_start();

// Verificar si el header 'Authentication-Method' está presente
if (isset($_SERVER['HTTP_AUTHENTICATION_METHOD'])) {
    // Recuperar el valor del header
    $authMethod = $_SERVER['HTTP_AUTHENTICATION_METHOD'];

    // Almacenar el valor en una variable de sesión
    $_SESSION['authMethod'] = $authMethod;

    // Responder con un mensaje de éxito
    echo json_encode(['status' => 'success', 'metodo' => $authMethod , 'message' => 'Método de autenticación almacenado en sesión.']);
} else {
    // Responder con un mensaje de error si el header no está presente
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Header de método de autenticación no encontrado.']);
}
