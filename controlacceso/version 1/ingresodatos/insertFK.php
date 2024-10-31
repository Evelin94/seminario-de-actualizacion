<?php
include 'conn.php';

// ARRAY USUARIOS
$usuarios = array(
    array("Usuario" => "Carlos", "Password" => "1234", "Grupo" => "verdulero"),
    array("Usuario" => "María", "Password" => "5678", "Grupo" => "cliente"),
    array("Usuario" => "Juan", "Password" => "abcd", "Grupo" => "verdulero")
);

// ITERAR ARRAY - INSERTAR 
foreach ($usuarios as $usuario) {
    $nombre = $usuario["Usuario"];
    $Password = $usuario["Password"];
    $grupo = $usuario["Grupo"];

    // OBTENER ID DEL GRUPO
    $sql_grupo = "SELECT idGrupo FROM grupos WHERE Grupo = '$grupo'";
    $result_grupo = $conn->query($sql_grupo);

    // VERIFICAR SI SE ENCONTRO EL GRUPO
    if ($result_grupo) {
        if ($result_grupo->num_rows > 0) {
            // OBTENER EL ID DEL GRUPO
            $row = $result_grupo->fetch_assoc();
            $idGrupo = $row["idGrupo"];

            $sql_insert = "INSERT INTO usuarios (Usuario, Password, Grupos_idGrupo) VALUES ('$nombre', '$Password', $idGrupo)";

            if ($conn->query($sql_insert) === TRUE) {
                echo "Usuario '$nombre' ingresado correctamente.<br>";
            } else {
                echo "Error al ingresar usuario '$nombre': " . $conn->error . "<br>";
            }
        } else {
            echo "No se encontró el grupo '$grupo'.<br>";
        }
    } else {
        echo "Error al ejecutar la consulta: " . $conn->error . "<br>";
    }
}

// Acciones y grupos correspondientes
$verdulero = array( 0, 1, 2, 3);
$cliente = array(4, 5);
$grupos_acciones = array(0 => $verdulero, 1 => $cliente);

// Insertar permisos para cada grupo
foreach ($grupos_acciones as $grupo => $acciones) {
    foreach ($acciones as $accion) {
        // Consulta para insertar el permiso
        $sql_insert = "INSERT INTO permisos (Grupos_idGrupo, Acciones_idAccion) VALUES ($grupo, $accion)";

        // Ejecutar consulta de inserción
        if ($conn->query($sql_insert) === TRUE) {
            echo "Permiso para Grupo $grupo y Acción $accion ingresado correctamente.<br>";
        } else {
            echo "Error al ingresar permiso para Grupo $grupo y Acción $accion: " . $conn->error . "<br>";
        }
    }
}























// Cerrar conexión
$conn->close();
