<?php
include("../conexion.php");

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['orden_id']) || !is_numeric($data['orden_id'])) {
    echo json_encode(["success" => false, "error" => "ID inválido"]);
    exit;
}

$id = intval($data['orden_id']);

$sql = "DELETE FROM ordenes WHERE orden_id = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(["success" => false, "error" => $conexion->error]);
    exit;
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
