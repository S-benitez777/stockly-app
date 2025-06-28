<?php
include("../conexion.php");

$categorias = [];

if ($resultado = $conexion->query("SELECT nombre FROM categorias ORDER BY nombre ASC")) {
    while ($row = $resultado->fetch_assoc()) {
        $categorias[] = $row['nombre'];
    }
    $resultado->free();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta: ' . $conexion->error]);
    exit;
}

$conexion->close();

echo json_encode($categorias);
?>
