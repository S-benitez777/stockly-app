<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=sistema_de_gestion_de_inventarios", "root", "");
    echo "✅ Conexión exitosa a la base de datos.";
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
?>
