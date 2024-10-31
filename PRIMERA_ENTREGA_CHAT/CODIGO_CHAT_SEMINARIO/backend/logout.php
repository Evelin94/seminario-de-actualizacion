<?php
include('conn.php');
session_start();

// Verificar si la sesión está iniciada y si existe la variable 'idSesion'
if (isset($_SESSION['idsesion'])) {
    $idSesion = $_SESSION['idsesion'];

    // Preparar y ejecutar la consulta SQL para actualizar finSesion
    $sqlUpdate = "UPDATE sesiones SET finSesion = NOW() WHERE idSesion = :idSesion";
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->bindParam(':idSesion', $idSesion);
    $stmtUpdate->execute();
}

// Destruir la sesión actual
session_destroy();

// Redirigir al usuario a la página de inicio de sesión o la página principal
header("Location: ../index.html");
exit;
?>
