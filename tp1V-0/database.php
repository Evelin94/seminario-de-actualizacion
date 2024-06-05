<?php
$connection = null;

try {
    $connection = new PDO('mysql:host=localhost; port: 3306;dbname=verduleria', 'root', '12345678');
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->exec("USE verduleria");
} catch (PDOException $connectionException) {
    $status = array('status' => 'db-error', 'description' => $connectionException->getMessage());
    echo json_encode($status);
    die();
}
?>

 


