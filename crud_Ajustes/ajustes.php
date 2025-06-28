<?php
session_start();
include('../conexion.php');
$id = $_SESSION['usuario_id'] ?? 1; // Cambia esto según tu sistema de sesiones

// Obtener datos del usuario
$res = $conexion->query("SELECT nombre, correo, foto_perfil FROM usuarios WHERE id = $id");
$user = $res->fetch_assoc();
$foto = $user['foto_perfil'] ?: 'iconos/foto de perfil.png';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ajustes de Perfil</title>
</head>
<body>
  <h1>Ajustes de Perfil</h1>
  <img src="<?php echo $foto; ?>" alt="Foto de perfil" width="100"><br>
  <form action="subir_foto.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="foto">
    <button type="submit">Cambiar foto</button>
  </form>
  <form action="actualizar_perfil.php" method="POST">
    <label>Nombre: <input type="text" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>"></label><br>
    <label>Correo: <input type="email" name="correo" value="<?php echo htmlspecialchars($user['correo']); ?>"></label><br>
    <button type="submit">Guardar cambios</button>
  </form>
  <form action="cambiar_contraseña.php" method="POST">
    <label>Nueva contraseña: <input type="password" name="nueva_contraseña"></label>
    <button type="submit">Cambiar contraseña</button>
  </form>
</body>
</html>