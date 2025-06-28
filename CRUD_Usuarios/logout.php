<?php
session_start();
$_SESSION = [];
session_destroy();

if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

header("Location: inicio_de_sesion.php");
exit();
?>
