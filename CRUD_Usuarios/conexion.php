<?php
$db_user = 'root';
$db_pass = '';
$db_host = '127.0.0.1';
$db_port = '3307';
$db_name = 'sistema_de_gestion_de_inventarios';

$conexion = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
if ($conexion->connect_error) {
    die('Error de conexión a la base de datos: ' . $conexion->connect_error);
}
?>