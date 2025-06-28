<?php
session_start();
include('../conexion.php');
$id = $_SESSION['usuario_id'] ?? 1;

$nueva = $_POST['nueva_contraseña'] ?? '';

if ($nueva && strlen($nueva) >= 6) {
    $hash = password_hash($nueva, PASSWORD_DEFAULT);
    if ($stmt = $conexion->prepare("UPDATE usuarios SET password=? WHERE id=?")) {
        $stmt->bind_param("si", $hash, $id);
        $stmt->execute();
        $stmt->close();
        header('Location: ajustes.php?msg=contraseña_actualizada');
    } else {
        header('Location: ajustes.php?error=error_bd');
    }
} else {
    header('Location: ajustes.php?error=contraseña_invalida');
}
exit;