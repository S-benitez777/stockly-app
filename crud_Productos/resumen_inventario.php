<?php
require 'config.php';
header('Content-Type: application/json');

try {
    $resumen = [
        'total_categorias' => 0,
        'total_productos' => 0,
        'total_ingresos' => 0,
        'top_vendidos' => [],
        'valor_top_vendidos' => 0,
        'poco_disponibles' => 0,
        'agotados' => 0
    ];

    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM categorias");
    $resumen['total_categorias'] = (int) ($stmt->fetch()['total'] ?? 0);

    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM productos");
    $resumen['total_productos'] = (int) ($stmt->fetch()['total'] ?? 0);

    $stmt = $pdo->query("SELECT SUM(precio_venta) AS ingresos FROM productos");
    $resumen['total_ingresos'] = (float) ($stmt->fetch()['ingresos'] ?? 0);

    // Puedes reemplazar esto con una consulta real si lo necesitas
    $resumen['top_vendidos'] = [];
    $resumen['valor_top_vendidos'] = 0;

    $stmt = $pdo->query("SELECT COUNT(*) AS pocos FROM productos WHERE stock_minimo <= 5");
    $resumen['poco_disponibles'] = (int) ($stmt->fetch()['pocos'] ?? 0);

    $stmt = $pdo->query("SELECT COUNT(*) AS agotados FROM productos WHERE stock_minimo = 0");
    $resumen['agotados'] = (int) ($stmt->fetch()['agotados'] ?? 0);

    echo json_encode($resumen);
} catch (PDOException $e) {
    echo json_encode(['error' => 'No se pudo generar el resumen']);
}
?>
