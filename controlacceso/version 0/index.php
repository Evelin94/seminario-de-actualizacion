<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Permisos</title>
    <link rel="stylesheet" href="style.css">
    <script src="index.js"></script>

</head>

<body>
    <div class="container">
        <h3>Consultar Permisos</h3>
        <form id="formConsulta">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required>
            <input type="submit" value="Consultar">
        </form>

        <div id="permisos">
            <h3>Permisos del usuario</h3>
            <ul id="permisosLista"></ul>
        </div>
    </div>
   
</body>

</html>

