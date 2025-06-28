<?php
require 'db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);

try {
    $stmt = $pdo->prepare("UPDATE proveedores SET 
        nombre_legal = ?, 
        nombre_comercial = ?, 
        rfc = ?, 
        direccion = ?, 
        telefono = ?, 
        email_contacto = ?, 
        dias_credito = ?, 
        limite_credito = ?, 
        esta_activo = ?
    WHERE proveedor_id = ?");

    $stmt->execute([
        $data['nombre_legal'],
        $data['nombre_comercial'],
        $data['rfc'],
        $data['direccion'],
        $data['telefono'],
        $data['email_contacto'],
        $data['dias_credito'],
        $data['limite_credito'],
        $data['esta_activo'],
        $data['proveedor_id']
    ]);

    echo json_encode(['status' => 'Proveedor actualizado correctamente']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
