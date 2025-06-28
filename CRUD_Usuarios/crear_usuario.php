<?php
require 'conexion.php';
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $password = trim($_POST['password']);

    if (empty($nombre)) $errores[] = "El nombre es obligatorio.";
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "Correo inválido.";
    if (strlen($password) < 6) $errores[] = "La contraseña debe tener al menos 6 caracteres.";

    if (empty($errores)) {
        $passwordEncriptada = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $correo, $passwordEncriptada);
        if ($stmt->execute()) {
            header('Location: listar_usuarios.php');
            exit;
        } else {
            $errores[] = "Error al insertar usuario: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <script src="validaciones.js" defer></script>
</head>
<body>
    <h1>Registrar Usuario</h1>
    <?php if (!empty($errores)): ?>
        <ul style="color: red;">
            <?php foreach ($errores as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form id="formCrearUsuario" method="POST">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>
        <label>Correo:</label><br>
        <input type="email" name="correo" required><br><br>
        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Registrar</button>
    </form>
</body>
</html>