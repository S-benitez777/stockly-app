<?php
require 'db_connection.php';

$id = $_GET['id'] ?? null;

if ($id === null) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de proveedor no especificado']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM proveedores WHERE proveedor_id = ?");
    $stmt->execute([$id]);

    echo json_encode(['status' => 'Proveedor eliminado correctamente']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
