<?php
header("Content-Type: application/json; charset=UTF-8");
include("conexion.php");

// Detectar acción
$accion = $_GET['accion'] ?? 'listar';

/* ========================
   1. LISTAR PRODUCTOS
   ======================== */
if ($accion == 'listar') {
    $sql = "SELECT * FROM productos";
    $result = $conn->query($sql);
    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    echo json_encode($productos);
    exit;
}

/* ========================
   2. AGREGAR PRODUCTO
   ======================== */
if ($accion == 'agregar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $nombre      = $conn->real_escape_string($data['nombre'] ?? '');
    $descripcion = $conn->real_escape_string($data['descripcion'] ?? '');
    $precio      = isset($data['precio']) ? floatval($data['precio']) : 0;
    $stock       = isset($data['stock']) ? intval($data['stock']) : 0;
    $id_estado   = intval($data['id_estado'] ?? 1);
    $id_categoria= intval($data['id_categoria'] ?? 1);
    $id_proveedor= intval($data['id_proveedor'] ?? 1);

    if (empty($nombre) || $precio <= 0) {
        echo json_encode(["error" => "Faltan campos obligatorios"]);
        exit;
    }

    $sql = "INSERT INTO productos (nombre_producto, Descripcion, Precio, Stock, id_estado, id_categoria, id_proveedor)
            VALUES ('$nombre','$descripcion','$precio','$stock','$id_estado','$id_categoria','$id_proveedor')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Producto agregado correctamente", "id" => $conn->insert_id]);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
    exit;
}

/* ========================
   3. EDITAR PRODUCTO
   ======================== */
if ($accion == 'editar' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);

    $id          = intval($data['id_productos'] ?? 0);
    $nombre      = $conn->real_escape_string($data['nombre'] ?? '');
    $descripcion = $conn->real_escape_string($data['descripcion'] ?? '');
    $precio      = isset($data['precio']) ? floatval($data['precio']) : 0;
    $stock       = isset($data['stock']) ? intval($data['stock']) : 0;
    $id_estado   = intval($data['id_estado'] ?? 1);
    $id_categoria= intval($data['id_categoria'] ?? 1);
    $id_proveedor= intval($data['id_proveedor'] ?? 1);

    if ($id <= 0) {
        echo json_encode(["error" => "ID inválido"]);
        exit;
    }

    $sql = "UPDATE productos 
            SET nombre_producto='$nombre', Descripcion='$descripcion', Precio='$precio', Stock='$stock',
                id_estado='$id_estado', id_categoria='$id_categoria', id_proveedor='$id_proveedor'
            WHERE id_productos='$id'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Producto actualizado correctamente"]);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
    exit;
}

/* ========================
   4. ELIMINAR PRODUCTO
   ======================== */
if ($accion == 'eliminar' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = intval($data['id_productos'] ?? 0);

    if ($id <= 0) {
        echo json_encode(["error" => "ID inválido"]);
        exit;
    }

    $sql = "DELETE FROM productos WHERE id_productos='$id'";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Producto eliminado correctamente"]);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
    exit;
}

/* ========================
   5. ACCIÓN INVÁLIDA
   ======================== */
echo json_encode(["error" => "Acción no válida"]);
?>