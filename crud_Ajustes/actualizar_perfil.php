<?php
session_start();
include('../conexion.php');
$id = $_SESSION['usuario_id'] ?? 1;

$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';

if ($nombre && $correo) {
    if ($conexion) {
        $stmt = $conexion->prepare("UPDATE usuarios SET nombre=?, correo=? WHERE id=?");
        if ($stmt) {
            $stmt->bind_param("ssi", $nombre, $correo, $id);
            $stmt->execute();
            $stmt->close();
            header('Location: ajustes.php?msg=perfil_actualizado');
            exit;
        } else {
            header('Location: ajustes.php?error=stmt_failed');
            exit;
        }
    } else {
        header('Location: ajustes.php?error=db_connection');
        exit;
    }
} else {
    header('Location: ajustes.php?error=campos_vacios');
    exit;
}
?>