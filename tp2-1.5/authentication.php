<?php

function getAuthenticatedUser($connection) {
    if (!isset($_SERVER['HTTP_X_USERNAME']) || !isset($_SERVER['HTTP_X_PASSWORD'])) {
        return array('success' => false, 'message' => 'Credenciales no proporcionadas');
    }

    $username = $_SERVER['HTTP_X_USERNAME'];
    $password = $_SERVER['HTTP_X_PASSWORD'];

    try {
        $stmt = $connection->prepare("SELECT id, name, group_id, password FROM user WHERE name = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            return array('success' => false, 'message' => 'Autenticacion fallida');
        }

        return array('success' => true, 'user' => $user);
    } catch (PDOException $e) {
        return array('success' => false, 'message' => 'Error al ejecutar la consulta de autenticacion: ' . $e->getMessage());
    }
}

function checkPermission($connection, $groupId, $action) {
    try {
        $stmtGroup = $connection->prepare("SELECT action_id FROM group_action WHERE group_id = :group_id AND action_id = :action_id");
        $stmtGroup->bindParam(':group_id', $groupId);
        $stmtGroup->bindParam(':action_id', $action);
        $stmtGroup->execute();
        $allowedActions = $stmtGroup->fetchAll(PDO::FETCH_COLUMN);

        if (!in_array($action, $allowedActions)) {
            return array('success' => false, 'message' => 'Privilegios insuficientes ' . $action);
        }

        return array('success' => true);
    } catch (PDOException $e) {
        return array('success' => false, 'message' => 'Error al ejecutar la consulta de permisos: ' . $e->getMessage());
    }
}

?>
