<?php
session_start();
include('../conexion.php');
$id = $_SESSION['usuario_id'] ?? 1;

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nombreArchivo = 'perfil_' . $id . '.' . $ext;
    $ruta = '../uploads/usuarios/' . $nombreArchivo;
    move_uploaded_file($_FILES['foto']['tmp_name'], $ruta);

    // Guarda la ruta en la base de datos (ajusta la ruta según tu estructura)
    $stmt = $conexion->prepare("UPDATE usuarios SET foto_perfil=? WHERE id=?");
    $ruta_db = 'uploads/usuarios/' . $nombreArchivo;
    $stmt->bind_param("si", $ruta_db, $id);
    $stmt->execute();
    $stmt->close();
    header('Location: ajustes.php?msg=foto_actualizada');
} else {
    header('Location: ajustes.php?error=error_foto');
}
exit;