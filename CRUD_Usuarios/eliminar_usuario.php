<?php
require 'conexion.php';
$id = $_GET['id'] ?? null;
if (!$id) die("ID no válido");
$stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    header("Location: listar_usuarios.php");
    exit;
} else {
    echo "Error al eliminar usuario.";
}
?>