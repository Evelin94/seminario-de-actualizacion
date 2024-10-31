<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    // Redirigir al formulario de inicio de sesión si no está autenticado
    header("Location: ../index.html");
    exit;
}

$nombreUsuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .logo {
            height: 40px;
            margin-right: 10px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        .user-name {
            margin-right: 20px;
            font-size: 16px;
        }

        .logout-link {
            text-decoration: none;
            color: #007bff;
        }

        .logout-link:hover {
            text-decoration: underline;
        }

        .content {
            margin-top: 50px;
            text-align: center;
        }

        #formAuth {
            border: 2px solid #e0e0e0;
            padding: 20px;
            display: inline-block;
            text-align: left;
            margin-top: 10px;
        }

        #title {
            font-weight: bold;
        }

        #formAuth .radio-container {
            display: block;
            align-items: left;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        #backButton {
            width: 15%;
            padding: 10px;
            background-color: #0056b3;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 30px;
        }

        #buttondiv {
            display: flex;
            justify-content: center;
        }

        .button {
            display: inline-block;
            background-color: #007BFF;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .button:hover {
            background-color: #0056b3;
        }

        #radiobuttons {
            margin-top: 20px;
        }
    </style>
    <script src="./metodoSesion.js"></script>


</head>

<body>

    <header class="header">
        <div class="header-left">
            <img src="../img/watcher.png" alt="Logo" class="logo">
            <div class="title">Dashboard</div>
        </div>
        <div class="header-right">
            <div class="user-name"><?php echo htmlspecialchars($nombreUsuario); ?></div>
            <a href="logout.php" class="logout-link">Cerrar sesión</a>
        </div>
    </header>

    <!-- Contenido de la página -->
    <div class="content">
        <p>CONTENIDO DE LA PAGINA RESERVADA A USUARIOS AUTENTICADOS .....</p>

        <form id="formAuth" method="post">
            <div>
                <label for="authMethod" id="title">Método de autenticación:</label>
                <div id="radiobuttons">
                    <div class="radio-container">
                        <input type="radio" id="userPass" name="authMethod" value="userPass" checked>
                        <label for="userPass">Usuario y Contraseña</label>
                    </div>
                    <div class="radio-container">
                        <input type="radio" id="token" name="authMethod" value="token">
                        <label for="token">Token</label>
                    </div>
                </div>
            </div>
        </form>

        <div id="buttondiv">
            <a href="./crud/dashboard.html" class="button" id="backButton">Menú Crud</a>
        </div>
    </div>

</body>

</html>