<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");
require 'config.php';

try {
    $stmt = $pdo->query("SELECT proveedor_id AS id, nombre_comercial AS nombre FROM proveedores WHERE esta_activo = 1");
    $proveedores = $stmt->fetchAll();
    echo json_encode($proveedores);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al obtener proveedores',
        'detalle' => $e->getMessage()
    ]);
}
?>
