<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agenda_contactos";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

header('Content-Type: application/json'); 


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT c.id, c.nombre, c.email, c.direccion, GROUP_CONCAT(t.numero SEPARATOR ', ') AS telefonos
            FROM contactos c
            LEFT JOIN telefonos t ON c.id = t.id_contacto
            GROUP BY c.id";
    $result = $conn->query($sql);
    $contactos = [];

    while ($row = $result->fetch_assoc()) {
        $contactos[] = $row;
    }

    echo json_encode($contactos);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $nombre = $data['nombre'] ?? '';
    $email = $data['email'] ?? '';
    $direccion = $data['direccion'] ?? '';
    $telefono = $data['telefono'] ?? '';

   
    if (empty($nombre) || empty($email) || empty($direccion) || empty($telefono)) {
        echo json_encode(['success' => false, 'error' => 'Faltan campos necesarios']);
        exit;
    }

   
    $stmt = $conn->prepare("INSERT INTO contactos (nombre, email, direccion) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $email, $direccion);

    if ($stmt->execute()) {
        $contacto_id = $stmt->insert_id;

        $stmt_telefono = $conn->prepare("INSERT INTO telefonos (id_contacto, numero) VALUES (?, ?)");
        $stmt_telefono->bind_param("is", $contacto_id, $telefono);
        $stmt_telefono->execute();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'] ?? 0;
    $nombre = $data['nombre'] ?? '';
    $email = $data['email'] ?? '';
    $direccion = $data['direccion'] ?? '';
    $telefono = $data['telefono'] ?? '';

    if (empty($id) || empty($nombre) || empty($email) || empty($direccion) || empty($telefono)) {
        echo json_encode(['success' => false, 'error' => 'Faltan campos necesarios']);
        exit;
    }
    
    $stmt = $conn->prepare("UPDATE contactos SET nombre=?, email=?, direccion=? WHERE id=?");
    $stmt->bind_param("sssi", $nombre, $email, $direccion, $id);

    if ($stmt->execute()) {
        $stmt_telefono = $conn->prepare("UPDATE telefonos SET numero=? WHERE id_contacto=?");
        $stmt_telefono->bind_param("si", $telefono, $id);
        $stmt_telefono->execute();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'] ?? 0;

    if (empty($id)) {
        echo json_encode(['success' => false, 'error' => 'Falta el ID']);
        exit;
    }

   
    $stmt = $conn->prepare("DELETE FROM contactos WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
}


$conn->close();
?>
