<?php

include_once('./database.php');

if (!$connection) {
    $status = array('success' => false, 'message' => 'Database connection not established.');
    echo json_encode($status);
    die();
}

$json_body = file_get_contents('php://input');
file_put_contents('debug.log', "Contenido del cuerpo JSON recibido: \n" . $json_body . "\n", FILE_APPEND); 

$object = json_decode($json_body);
file_put_contents('debug.log', "Contenido decodificado: \n" . print_r($object, true) . "\n", FILE_APPEND);


$operation = $object->operation ?? null;
$data = $object->data ?? null;
file_put_contents('debug.log', "Operation: $operation\nData: " . print_r($data, true) . "\n", FILE_APPEND);


$operation= $operation ?? null;
if ($data && $operation) {


    try {
        
        switch ($operation) {
            case 'user':
                $username = $data->username ?? null;
                $password = $data->password ?? null;
               
                if ($username && $password) {
                   
                    $SQLCode = "INSERT INTO User (Name, Password) VALUES (:username, :password)";
            
                    $statement = $connection->prepare($SQLCode);
                    $statement->bindParam(':username', $username);
                    $statement->bindParam(':password', $password);
                    $statement->execute();
                    $status = array('success' => true, 'message' => 'Usuario creado satisfactoriamente!');
                } else {
                    $status = array('success' => false, 'message' => 'Datos incompletos para usuario');
                }
                break;
            case 'group':
                
                $groupName = $data->groupName ?? null;
        $groupName =$data -> groupName ?? null;
            //    if ($groupName) {
            
                    $SQLCode = "INSERT INTO `Group` (Name) VALUES (:groupName)";
                    $statement = $connection->prepare($SQLCode);
                    $statement->bindParam(':groupName', $groupName);
                    $statement->execute();
                    $status = array('success' => true, 'message' => 'Grupo creado satisfactoriamente!');
              //  } else {
              //      $status = array('success' => false, 'message' => 'Datos incompletos para grupo');
             //   }
                break;
            case 'action':
            
                $actionName = $_POST['nombre-accion'] ?? null;
                if ($actionName) {
            
                    $SQLCode = "INSERT INTO Action (Name) VALUES (:actionName)";
                    $statement = $connection->prepare($SQLCode);
                    $statement->bindParam(':actionName', $actionName);
                    $statement->execute();
                    $status = array('success' => true, 'message' => 'Accion creada satisfactoriamente!');
                } else {
                    $status = array('success' => false, 'message' => 'Datos incompletos para accion');
                }
                break;
            default:
                $status = array('success' => false, 'message' => 'Operacion no valida');
        }
       
        echo json_encode($status);
    } catch (PDOException $connectionException) {
       
        $status = array('success' => false, 'message' => $connectionException->getMessage());
      
        echo json_encode($status);
      
        die();
    }
} else {
  
    $status = array('success' => false, 'message' => 'Operacion o datos no recibidos');
    
    echo json_encode($status);
 
    die();
}
?>
