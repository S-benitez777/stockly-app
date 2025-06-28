<?php
header('Content-Type: application/json');
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=sistema_de_gestion_de_inventarios", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Error al conectar con la base de datos",
        "detalle" => $e->getMessage()
    ]);
    exit;
}
?>