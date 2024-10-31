<?php
include 'conn.php';

// RECIBIR PETICION JSON
$data = json_decode(file_get_contents('php://input'), true);

if ($data && isset($data['usuario']) && isset($data['contraseña'])) {    

    $usuario = $data['usuario'];
    $contraseña = $data['contraseña'];
    $existe = false;

    // Llamar al procedimiento almacenado
    $stmt = $conn->prepare("CALL VerificarUsuario(?, ?, @existe)");
    $stmt->bind_param("ss", $usuario, $contraseña);
    $stmt->execute();

    // Recoger el resultado
    $result = $conn->query("SELECT @existe AS existe");
    $row = $result->fetch_assoc();
    $existe = $row['existe'];

    // VERIFICAR SI EXISTE EL REGISTRO 
    if ($existe ) {
   
        // Consulta para obtener el Grupos_idGrupo del usuario
        $stmt_usuario = $conn->prepare("SELECT Grupos_idGrupo FROM usuarios WHERE idUsuario = ?");
        $stmt_usuario->bind_param("s", $usuario);
        $stmt_usuario->execute();
        $stmt_usuario->bind_result($idGrupo);
        $stmt_usuario->fetch();
        $stmt_usuario->close();


        $sql_grupo = "SELECT * FROM Grupos WHERE idGrupo = '$idGrupo'";
        $result_grupo = $conn->query($sql_grupo);

        
        if ($result_grupo->num_rows > 0) {
            // OBTENER GRUPO DEL USUARIO
            $row_grupo = $result_grupo->fetch_assoc();

            $sql_acciones = "SELECT Accion FROM Acciones WHERE idAccion IN (SELECT Acciones_idAccion FROM permisos WHERE Grupos_idGrupo = '$idGrupo')";
            $result_acciones = $conn->query($sql_acciones);

       
            if ($result_acciones->num_rows > 0) {

                // ALMACENAR ACCIONES DEL USUARIO
                $acciones_data = array();              
                while ($row_acciones = $result_acciones->fetch_assoc()) {
                    $acciones_data[] = $row_acciones['Accion'];
                }

                
                $respuesta = array(
                    "grupo" => $row_grupo,
                    "acciones" => $acciones_data
                );

                // RESPUESTA JSON
                echo json_encode($respuesta);
            } else {
                // NO SE ENCONTRARON ACCIONES
                echo json_encode(array("mensaje" => "No se encontraron acciones para este grupo"));
            }
        } else {
            // NO SE ENCONTRO EL GRUPO
            echo json_encode(array("mensaje" => "No se encontró información del grupo"));
        }
    } else {
        // USUARIO NO VALIDADO
        echo json_encode(array("mensaje" => "Usuario o Contraseña Incorrectos."));
    }

    // CERRAR CONEXION
        $conn->close();
} else {
    // DATOS NO INGRESADOS
    echo json_encode(array("mensaje" => "Datos de usuario y contraseña no proporcionados"));
}

