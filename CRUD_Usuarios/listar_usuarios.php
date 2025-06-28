<?php
require 'conexion.php';
$sql = "SELECT id, nombre, correo FROM usuarios";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #aaa; padding: 10px; text-align: center; }
        th { background-color: #a9e04b; color: white; }
        a { margin: 0 5px; color: #3d7797; text-decoration: none; font-weight: bold; }
        a:hover { text-decoration: underline; }
        .btn-agregar { display: block; width: 200px; margin: 20px auto;
            background-color: #3d7797; color: white; text-align: center;
            padding: 10px; text-decoration: none; border-radius: 5px; }
        .btn-agregar:hover { background-color: #2b5d75; }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Usuarios Registrados</h1>
    <a class="btn-agregar" href="crear_usuario.php">+ Agregar nuevo usuario</a>
    <table>
        <tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Acciones</th></tr>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($fila['id']) ?></td>
            <td><?= htmlspecialchars($fila['nombre']) ?></td>
            <td><?= htmlspecialchars($fila['correo']) ?></td>
            <td>
                <a href="editar_usuario.php?id=<?= $fila['id'] ?>">Editar</a>
                <a href="eliminar_usuario.php?id=<?= $fila['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>