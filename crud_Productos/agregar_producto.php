<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si el Content-Type es JSON, decodifica el cuerpo
    $input = json_decode(file_get_contents('php://input'), true);

    $nombre = $input['nombre'] ?? '';
    $sku = $input['sku'] ?? '';
    $categoria_id = $input['categoria_id'] ?? null;
    $proveedor_id = $input['proveedor_id'] ?? null;
    $costo_promedio = $input['costo_promedio'] ?? 0;
    $stock = $input['stock'] ?? 0;
    $es_perecedero = $input['es_perecedero'] ?? 0;
    $creado_por = $input['creado_por'] ?? 1;

    try {
        $stmt = $pdo->prepare("INSERT INTO productos (sku, nombre, categoria_id, proveedor_id, costo_promedio, stock_minimo, controla_inventario, es_perecedero, creado_por)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $sku, $nombre, $categoria_id, $proveedor_id, $costo_promedio,
            $stock, 1, $es_perecedero, $creado_por
        ]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo guardar el producto', 'detalle' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
?>