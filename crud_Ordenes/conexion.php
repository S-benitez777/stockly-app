<?php

$conexion = new mysqli('localhost', 'root', '', 'sistema_de_gestion_de_inventarios', 3307);
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode([
        "error" => "Error al conectar con la base de datos",
        "detalle" => $conexion->connect_error
    ]);
    exit;
}
?>