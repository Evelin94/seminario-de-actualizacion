<?php
// Configuración de la conexión a la base de datos
$host = "localhost";
$dbname = "nombre_de_la_base_de_datos?";
$username = "nombre_de_usuario?";
$password = "contraseña?";

try 
{
  // Crear una nueva instancia de conexión a la base de datos
  $connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  // Obtener el ID del usuario a través del método GET
  $usuario_id = json_decode(file_get_contents('php://input'));

  // Generación del código SQL
  $sql = "SELECT * FROM usuarios WHERE id=$usuario_id";

  // Ejecutar la sentencia SQL
  $stmt = $connection->query($sql);

  // Obtener los resultados
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Cerrar la conexión a la base de datos
  $connection = null;

  // Devolver los resultados en formato JSON
  header('Content-Type: application/json');
  echo json_encode($results);
} catch (PDOException $e) 
{
  echo "Error: " . $e->getMessage();
}


?>
