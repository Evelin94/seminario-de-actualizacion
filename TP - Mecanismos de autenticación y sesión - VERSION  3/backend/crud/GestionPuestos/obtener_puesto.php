<?php
session_start();
include('../conn.php');
include('../verificarSesion.php');
include('../generarNewToken.php');

if (validateSessionToken($htoken)) {
try {
    // Conexión a la base de datos usando PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Leer el cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['positionId'])) {
        $positionId = $input['positionId'];
      
        $stmt = $pdo->prepare(
                'SELECT puestos.idPuestos, usuarios.Usuario, grupos.Grupo
             FROM puestos
             INNER JOIN usuarios ON puestos.Usuarios_idUsuarios = usuarios.idUsuarios
             INNER JOIN grupos ON puestos.Grupos_idGrupos = grupos.idGrupos
             WHERE puestos.idPuestos = :idPuestos'
            );

        $stmt->bindParam(':idPuestos', $positionId, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Devolver el registro combinado en formato JSON
            echo json_encode($result);
        } else {
            // Devolver un mensaje de error si no se encuentra el puesto
            echo json_encode(['error' => 'No se encontró el puesto con el ID especificado']);
        }
    } else {
        echo json_encode(['error' => 'No se proporcionó el ID del puesto']);
    }
} catch (PDOException $e) {
    // Manejo de errores en la conexión o ejecución de la consulta
    echo json_encode(['error' => 'Error al conectar a la base de datos: ' . $e->getMessage()]);
}
} else {
    echo json_encode(['error' => 'Session token not provided']);
}