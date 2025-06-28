<?php
include("../conexion.php");
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

// Validar que todos los campos requeridos existen
$required = [
    'nombre_producto', 'producto_id', 'categoria', 'valor_orden', 'cantidad',
    'unidad', 'precio_compra', 'fecha_entrega', 'estado', 'usuario_id',
    'producto_db_id', 'orden_id'
];

foreach ($required as $field) {
    if (!isset($data[$field])) {
        echo json_encode(["success" => false, "error" => "Falta el campo: $field"]);
        exit;
    }
}

$sql = "UPDATE ordenes SET nombre_producto=?, producto_id=?, categoria=?, valor_orden=?, cantidad=?, unidad=?, precio_compra=?, fecha_entrega=?, estado=?, usuario_id=?, producto_db_id=? WHERE orden_id=?";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(["success" => false, "error" => "Error en prepare: " . $conexion->error]);
    exit;
}

// Revisar los tipos de datos para bind_param
// s = string, i = integer, d = double
$stmt->bind_param(
    "sssdisdssiii",
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
    $data['producto_db_id'],
    $data['orden_id']
);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Error en execute: " . $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
