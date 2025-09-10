<?php
include("conexion.php");

$accion = $_GET['accion'] ?? 'listar';

if ($accion == 'listar') {
    $sql = "SELECT * FROM productos";
    $result = $conn->query($sql);

    $productos = [];
    while($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    echo json_encode($productos);
}

if ($accion == 'agregar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $nombre = $conn->real_escape_string($data['nombre']);
    $descripcion = $conn->real_escape_string($data['descripcion']);
    $precio = $conn->real_escape_string($data['precio']);
    $stock = $conn->real_escape_string($data['stock']);

  
    $sql = "INSERT INTO productos (nombre_producto, Descripcion, Precio, Stock)
        VALUES ('$nombre','$descripcion','$precio','$stock')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "ok"]);
    } else {
        echo json_encode(["status" => "error", "msg" => $conn->error, "sql" => $sql]);
    }
}
?>