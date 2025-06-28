<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: registro.php");
    exit();
}

function limpiar_dato($dato) {
    return htmlspecialchars(strip_tags(trim($dato)));
}

function validar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

$username = limpiar_dato($_POST['username'] ?? '');
$nombre_completo = limpiar_dato($_POST['nombre_completo'] ?? '');
$email = limpiar_dato($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$_SESSION['form_data'] = [
    'username' => $username,
    'nombre_completo' => $nombre_completo,
    'email' => $email
];

$errores = [];

if (empty($username) || empty($nombre_completo) || empty($email) || empty($password)) {
    $errores[] = "Todos los campos son obligatorios.";
}
if (strlen($username) < 3 || strlen($username) > 50) {
    $errores[] = "El usuario debe tener entre 3 y 50 caracteres.";
}
if (strlen($nombre_completo) < 2 || strlen($nombre_completo) > 100) {
    $errores[] = "El nombre debe tener entre 2 y 100 caracteres.";
}
if (strlen($password) < 6) {
    $errores[] = "La contraseña debe tener al menos 6 caracteres.";
}
if (!validar_email($email)) {
    $errores[] = "El formato del correo electrónico no es válido.";
}
if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $errores[] = "El usuario solo puede contener letras, números y guiones bajos.";
}
if (!empty($errores)) {
    $_SESSION['error'] = implode(' ', $errores);
    header("Location: registro.php");
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT usuario_id FROM usuarios WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "El usuario o correo electrónico ya existe.";
        header("Location: registro.php");
        exit();
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, nombre_completo, email, esta_activo) VALUES (?, ?, ?, ?, 1)");
    $stmt->execute([$username, $password_hash, $nombre_completo, $email]);

    unset($_SESSION['form_data']);
    $_SESSION['usuario'] = $username;

    header("Location: dashboard.php");
    exit();

} catch (PDOException $e) {
    error_log("Error en registro: " . $e->getMessage());
    $_SESSION['error'] = "Error en la base de datos. Inténtelo más tarde.";
    header("Location: registro.php?error=database_error");
    exit();
}
?>