<?php
// Configurar las cabeceras para permitir solicitudes de otros orígenes
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Configuración de la base de datos
$servername = "localhost";
$username = "root";  // Cambia esto por tu usuario de la base de datos
$password = "";  // Cambia esto por tu contraseña de la base de datos
$dbname = "controlacceso1";
