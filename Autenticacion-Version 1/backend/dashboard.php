<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    // Redirigir al formulario de inicio de sesión si no está autenticado
    header("Location: index.html");
    exit;
}

$nombreUsuario = $_SESSION['usuario']; // Asumiendo que la variable de sesión 'nombre' contiene el nombre del usuario
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
    </style>
</head>

<body>

    <header class="header">
        <div class="header-left">
            <img src="../img/watcher.png" alt="Logo" class="logo">
            <div class="title">Dashboard</div>
        </div>
        <div class="header-right">
            <div class="user-name" ><?php echo htmlspecialchars($nombreUsuario); ?></div>
            <a href="logout.php" class="logout-link">Cerrar sesión</a>
        </div>
    </header>

    <!-- Contenido de la página -->
    <div class="content">
        <p style="margin-top: 50px; text-align:center;"> CONTENIDO DE LA PAGINA RESERVADA A USUARIOS AUTENTICADOS .....</p>
      
    </div>

</body>

</html>