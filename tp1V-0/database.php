<?php
$connection=null;
try {
    $connection = new PDO('mysql:host=localhost;port=3307;dbname=verduleria', 'root', '');
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connection successful!';
} catch (PDOException $connectionException) {
    $status = array( 'status'=>'db-error', 'description'=>$connectionException->getMessage() );
    echo 'Connection failed: ' . $connectionException->getMessage();
    die(); 
}
?>



