<?php
require 'db_connection.php';

if (!isset($pdo) || !$pdo) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed.']);
    exit;
}

try {
    $stmt = $pdo->query("SELECT * FROM proveedores");
    $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($proveedores);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
