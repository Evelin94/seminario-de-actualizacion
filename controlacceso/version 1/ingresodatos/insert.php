<?php
include 'conn.php';

// ARRAY GRUPOS
$grupos = array("Verdulero", "Cliente");

// ITERAR ARRAY E INSERTAR
foreach ($grupos as $grupo) {

    $sql = "INSERT INTO grupos (Grupo) VALUES ('$grupo')";    
    if ($conn->query($sql) === TRUE) {
        echo "Registro '$grupo' insertado correctamente.<br>";
    } else {
        echo "Error al insertar registro '$grupo': " . $conn->error;
    }
}


// ARRAY ACCIONES
$acciones = array("Agregar Producto", "Retirar Producto","Pesar Producto", "Emitir Factura", "Pedir Producto", "Pagar Factura");

// ITERAR ARRAY E INSERTAR
foreach ($acciones as $accion) {

    $sql1 = "INSERT INTO acciones (Accion) VALUES ('$accion')";
    if ($conn->query($sql1) === TRUE) {
        echo "Registro '$accion' insertado correctamente.<br>";
    } else {
        echo "Error al insertar registro '$accion': " . $conn->error;
    }
}
















// Cerrar conexión
$conn->close();
