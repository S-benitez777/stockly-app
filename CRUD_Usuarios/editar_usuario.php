<?php
require 'conexion.php';
$id = $_GET['id'] ?? null;
$errores = [];
if (!$id) die("ID no válido");
$stmt = $conexion->prepare("SELECT nombre, correo FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
if (!$usuario) die("Usuario no encontrado.");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    if (empty($nombre)) $errores[] = "Nombre obligatorio.";
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "Correo inválido.";
    if (empty($errores)) {
        $update = $conexion->prepare("UPDATE usuarios SET nombre = ?, correo = ? WHERE id = ?");
        $update->bind_param("ssi", $nombre, $correo, $id);
        if ($update->execute()) {
            header("Location: listar_usuarios.php");
            exit;
        } else {
            $errores[] = "Error al actualizar.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Editar Usuario</title></head>
<body>
    <h1>Editar Usuario</h1>
    <?php if (!empty($errores)): ?>
        <ul style="color: red;">
            <?php foreach ($errores as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="POST">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required><br><br>
        <label>Correo:</label><br>
        <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required><br><br>
        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>