<?php
include('../conn.php');
try {
    // Conexión a la base de datos usando PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Leer el cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['permisoId'])) {
        $permisoId = $input['permisoId'];
      
        $stmt = $pdo->prepare(
                'SELECT permisos.idPermisos, grupos.Grupo, acciones.Accion
             FROM permisos
             INNER JOIN grupos ON permisos.Grupos_idGrupos = grupos.idGrupos
             INNER JOIN acciones ON permisos.Acciones_idAcciones = acciones.idAcciones
             WHERE permisos.idPermisos = :permisoId'
            );

        $stmt->bindParam(':permisoId', $permisoId, PDO::PARAM_INT);

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
