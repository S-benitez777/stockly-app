<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
include(__DIR__ . '/conexion.php'); // Incluye la conexión MySQLi
$ordenes = [];

// Verifica que $conexion esté definida y sea una instancia de mysqli
if (!isset($conexion) || !($conexion instanceof mysqli) || $conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión: ' . (isset($conexion) && $conexion instanceof mysqli ? $conexion->connect_error : 'No se pudo crear la conexión')]);
    exit;
}
 
$sql = "SELECT * FROM ordenes ORDER BY fecha_creacion DESC";
$result = $conexion->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $ordenes[] = $row;
    }
    echo json_encode($ordenes);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta: ' . $conexion->error]);
}

$conexion->close();
?>