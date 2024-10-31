<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente - Acciones</title>
</head>

<body>

    <?php
    // Obtener el usuario y el grupo desde los parámetros de la URL
    $usuario = isset($_GET['usuario']) ? htmlspecialchars($_GET['usuario']) : 'Usuario desconocido';
    $grupo = isset($_GET['grupo']) ? htmlspecialchars($_GET['grupo']) : 'Grupo desconocido';

    echo "<p><b>Usuario: </b> $usuario</p>";
    echo "<p><b>Grupo:</b> $grupo</p>";
    ?>

    <p>Acciones permitidas:</p>
    <ul>
        <?php
        // Obtener las acciones desde los parámetros de la URL
        $acciones = array();
        foreach ($_GET as $key => $value) {
            if (strpos($key, 'accion') === 0) {
                $acciones[] = htmlspecialchars($value);
            }
        }

        // Mostrar las acciones en una lista
        if (!empty($acciones)) {
            foreach ($acciones as $accion) {
                echo "<li>$accion</li>";
            }
        } else {
            echo "<li>No se recibieron acciones.</li>";
        }
        ?>
    </ul>
</body>

</html>