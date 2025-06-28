<?php
require 'db_connection.php';

$id = $_GET['id'] ?? null;

if ($id === null) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de proveedor no especificado']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM proveedores WHERE proveedor_id = ?");
    $stmt->execute([$id]);
    $proveedor = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($proveedor ?: []);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
