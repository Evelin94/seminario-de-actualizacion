<?php
// Verificar si los parámetros fueron enviados correctamente

// Recibir la cadena de parámetros
$parametrosString = $_GET['parametros'];
// Convertir la cadena en un array separado por comas
$parametros = explode(',', $parametrosString);

// Ahora puedes utilizar el array $parametros como desees
// print_r($parametros);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar Contacto</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style1.css">
    <script src="controller.js"></script>
</head>

<body>

    <div class="w3-container">
        <h3>Borrar Contacto</h3>
        <a href="index.php"><button class="w3-button w3-round w3-white w3-hover-light-gray w3-border"><img src="imagenes/back.png" height="32" width="32"> Volver </button></a>
    </div>


    <div class="w3-container">
        <table id='tabladelete' class="w3-table w3-bordered">
            <thead>
                <tr class="tableheading">
                    <th>NOMBRE</th>
                    <th>APELLIDO</th>
                    <th>EMAIL</th>
                    <th>TELEFONO 1</th>
                    <th>TELEFONO 2</th>
                    <th>TELEFONO 3</th>
                </tr>
            </thead>

            <tbody id='bodytabledelete'>
                <td><?php echo $parametros[1]; ?> </td>
                <td><?php echo $parametros[2]; ?> </td>
                <td> <?php echo $parametros[3]; ?> </td>
                <td><?php echo $parametros[4]; ?> </td>
                <td><?php if (isset($parametros[5])) {
                        echo $parametros[5];
                    } ?></td>
                <td><?php if (isset($parametros[6])) {
                        echo $parametros[6];
                    } ?></td>
            </tbody>
        </table>

        <button id="botondelete" class="w3-button w3-right w3-margin-top w3-round w3-white w3-hover-light-gray w3-border" onclick="eliminar(<?php echo $parametros[0]; ?>)"><img src="imagenes/borrar.png" height="32" width="32" alt="Nuevo"> Borrar Registro</button>

    </div>
    <div class="w3-container w3-margin-top" id="mensaje"></div>

    </div>

</body>

</html>