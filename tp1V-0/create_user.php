<?php
include_once('./database.php');

if (!$connection) {
    $status = array('status' => 'db-error', 'description' => 'Database connection not established.');
    echo json_encode($status);
    die();
}

$json_body = file_get_contents('php://input');
$object = json_decode($json_body);

$password = $object->password ?? null;
$username = $object->username ?? null;

if ($username && $password) {
    try {
        $SQLCode = "INSERT INTO user(name, password) VALUES (:username, :password)";
        $statement = $connection->prepare($SQLCode);
        $statement->bindParam(':username', $username);
        $statement->bindParam(':password', $password);
        $statement->execute();

        $status = array('status' => 'ok', 'description' => 'Usuario creado satisfactoriamente!');
        echo json_encode($status);
    } catch (PDOException $connectionException) {
        $status = array('status' => 'db-error', 'description' => $connectionException->getMessage());
        echo json_encode($status);
        die();
    }
} else {
    $status = array('status' => 'error', 'description' => 'Datos incompletos');
    echo json_encode($status);
    die();
}
?>

