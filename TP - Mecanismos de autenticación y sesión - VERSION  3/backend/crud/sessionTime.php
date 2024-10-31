<?php
session_start();

// Tiempo de inactividad permitido (en segundos)
$inactivity_limit = 60; // 1 minuto

// Registrar la hora de la última actividad
if (isset($_SESSION['last_activity'])) {
    $inactive_duration = time() - $_SESSION['last_activity'];

    if ($inactive_duration > $inactivity_limit) {
        // La sesión ha expirado debido a inactividad
        session_unset(); // Elimina las variables de sesión
        session_destroy(); // Destruye la sesión

        // Responder con JSON indicando que la sesión ha expirado
        echo json_encode([
            'status' => 'expired',
            'message' => 'La sesión ha expirado debido a inactividad.',
            'tiempo' => $inactive_duration
        ]);
    } else {
        // Responder con JSON indicando que la sesión sigue activa
        echo json_encode([
            'status' => 'active',
            'message' => 'La sesión sigue activa.',
            'tiempo' => $inactive_duration
        ]);
        // Actualizar la hora de la última actividad
        $_SESSION['last_activity'] = time();

    }
} else {
    // Si no hay una hora de última actividad registrada, asumir que la sesión no ha comenzado
    echo json_encode([
        'status' => 'inactive',
        'message' => 'La sesión no ha comenzado o se ha reiniciado.',
        'tiempo' => 0
    ]);
}

// Actualizar la hora de la última actividad
$_SESSION['last_activity'] = time();
