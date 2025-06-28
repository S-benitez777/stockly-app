<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");
require 'config.php';

try {
    $stmt = $pdo->query("SELECT categoria_id AS id, nombre FROM categorias WHERE cuenta_inventario = 1");
    $categorias = $stmt->fetchAll();
    echo json_encode($categorias);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener categorías', 'detalle' => $e->getMessage()]);
}
?>
