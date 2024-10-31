<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactos</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="style.css">
    <script src="controller.js"></script>
</head>

<body>

    <div class="w3-container">
        <h2>Contactos</h2>
        <a href="createForm.php"><button class="w3-button w3-round w3-white w3-hover-light-gray w3-border"><img src="imagenes/crear.png" height="32" width="32" alt="Nuevo"> Nuevo </button></a>
    </div>

    <div class="w3-container">
        <table id='tabla' class="w3-table w3-bordered">
            <thead>
                <tr class="tableheading">
                    <th>NOMBRE</th>
                    <th>APELLIDO</th>
                    <th>EMAIL</th>
                    <th>TELEFONO 1</th>
                    <th>TELEFONO 2</th>
                    <th>TELEFONO 3</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>

            <tbody id='bodytable'></tbody>

        </table>
    </div>
</body>

</html>

<script>
    window.addEventListener('load', cargacontactos());   
</script>