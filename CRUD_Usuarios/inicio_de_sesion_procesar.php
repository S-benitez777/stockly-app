<?php

session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location:  http://localhost:8012/stockly/dashboard.php");
    exit();
}

function limpiar_dato($dato) {
    return htmlspecialchars(strip_tags(trim($dato)));
}

$username = limpiar_dato($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']) ? true : false;

$_SESSION['form_data'] = ['username' => $username];

if (empty($username) || empty($password)) {
    $_SESSION['error'] = "Todos los campos son obligatorios.";
    header("Location: inicio_de_sesion.php?error=empty_fields");
    exit();
}

$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    $_SESSION['error'] = "Usuario o contraseña incorrectos.";
    header("Location: inicio_de_sesion.php?error=invalid_credentials");
    exit();
}

if (!$usuario['esta_activo']) {
    $_SESSION['error'] = "Su cuenta está inactiva. Contacte al administrador.";
    header("Location: inicio_de_sesion.php?error=account_inactive");
    exit();
}

if (!password_verify($password, $usuario['password_hash'])) {
    $_SESSION['error'] = "Usuario o contraseña incorrectos.";
    header("Location: inicio_de_sesion.php?error=invalid_credentials");
    exit();
}

$_SESSION['user_id'] = $usuario['usuario_id'];
$_SESSION['username'] = $usuario['username'];
$_SESSION['nombre_completo'] = $usuario['nombre_completo'];
$_SESSION['email'] = $usuario['email'];
$_SESSION['logged_in'] = true;

// Actualizar último login
$stmt = $conexion->prepare("UPDATE usuarios SET ultimo_login = NOW() WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario['usuario_id']);
$stmt->execute();

unset($_SESSION['form_data']);

header("Location: dashboard.php");
exit();
?>