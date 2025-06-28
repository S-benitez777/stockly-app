<?php
include("../conexion.php");
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "error" => "No se recibieron datos válidos"]);
    exit;
}

// Validar campos requeridos
$required = [
    'nombre_producto', 'producto_id', 'categoria', 'valor_orden',
    'cantidad', 'unidad', 'precio_compra', 'fecha_entrega',
    'estado', 'usuario_id', 'producto_db_id'
];

foreach ($required as $field) {
    if (!isset($data[$field])) {
        echo json_encode(["success" => false, "error" => "Falta el campo: $field"]);
        exit;
    }
}

$sql = "INSERT INTO ordenes (nombre_producto, producto_id, categoria, valor_orden, cantidad, unidad, precio_compra, fecha_entrega, estado, usuario_id, producto_db_id)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(["success" => false, "error" => "Error en prepare: " . $conexion->error]);
    exit;
}

$stmt->bind_param(
    "sssdisdssii",
    $data['nombre_producto'],
    $data['producto_id'],
    $data['categoria'],
    $data['valor_orden'],
    $data['cantidad'],
    $data['unidad'],
    $data['precio_compra'],
    $data['fecha_entrega'],
    $data['estado'],
    $data['usuario_id'],
    $data['producto_db_id']
);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "id" => $stmt->insert_id]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
