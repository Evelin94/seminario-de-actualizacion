<?php
$connection = null;

try {
    $connection = new PDO('mysql:host=localhost;port:3306;dbname=login', 'root', '');
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->exec("USE login");
} catch (PDOException $connectionException) {
    $status = array('status' => 'db-error', 'description' => $connectionException->getMessage());
    echo json_encode($status);
    die();
}
?>