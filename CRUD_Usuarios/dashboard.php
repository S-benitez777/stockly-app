<?php  
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: http://localhost:8012/stockly/inicio_de_sesion.php");
    exit();
}
include(__DIR__ . '/conexion.php');

if (!isset($conexion) || !is_object($conexion) || $conexion->connect_error) {
    die("Error de conexión a la base de datos: " . (isset($conexion->connect_error) ? $conexion->connect_error : 'No se pudo establecer la conexión.'));
}

// Resumen de Ventas
$res_ventas = $conexion->query("SELECT 
    COUNT(*) AS total_ventas, 
    IFNULL(SUM(total_venta),0) AS total_ingresos, 
    IFNULL(SUM(margen_ganancia),0) AS total_beneficios
    FROM ventas");
if (!$res_ventas) {
    die("Error en la consulta de ventas: " . $conexion->error);
}
$ventas = $res_ventas->fetch_assoc() ?: ['total_ventas'=>0,'total_ingresos'=>0,'total_beneficios'=>0];

// Resumen de Inventario (stock total general desde inventario_ubicaciones)
$res_inventario = $conexion->query("SELECT 
    IFNULL(SUM(cantidad),0) AS cantidad_disponible
    FROM inventario_ubicaciones");
$inventario = $res_inventario ? $res_inventario->fetch_assoc() : ['cantidad_disponible'=>0];

// Resumen de Compras
$res_compras = $conexion->query("SELECT 
    COUNT(*) AS total_compras
    FROM ordenes");
$compras = $res_compras ? $res_compras->fetch_assoc() : ['total_compras'=>0];

// Resumen de Productos (proveedores y categorías)
$res_proveedores = $conexion->query("SELECT COUNT(DISTINCT proveedor_id) AS total_proveedores FROM productos");
$total_proveedores = $res_proveedores ? $res_proveedores->fetch_assoc()['total_proveedores'] : 0;

$res_categorias = $conexion->query("SELECT COUNT(DISTINCT categoria_id) AS total_categorias FROM productos");
$total_categorias = $res_categorias ? $res_categorias->fetch_assoc()['total_categorias'] : 0;

// Resumen de Ordenes
$res_ordenes = $conexion->query("SELECT COUNT(*) AS total_ordenes FROM ordenes");
$total_ordenes = $res_ordenes ? $res_ordenes->fetch_assoc()['total_ordenes'] : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Stock.ly</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../stockly_frontend/dashboard.css">
  <link rel="stylesheet" href="../stockly_frontend/producto.css">
  <link rel="stylesheet" href="../stockly_frontend/ordenes.css">
  <link rel="stylesheet" href="../stockly_frontend/proveedores.css">
  <link rel="stylesheet" href="../stockly_frontend/reportes.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <!-- Sidebar Navigation -->
  <div class="sidebar">
    <div class="logo-container">
      <img src="../stockly_frontend/imagenes/Logo stockly (1).png" alt="Stockly Logo" class="logo">
      <div class="brand-name">STOCK.LY</div>
    </div>
        <a href="dashboard.php" class="menu-item active" data-section="menu">
          <img src="../stockly_frontend/iconos/menu.svg" alt="Home Icon"> Menu
        </a>
        <a href="../crud_Productos/agregar_producto.php" class="menu-item" data-section="inventario">
          <img src="../stockly_frontend/iconos/inventario.svg" alt="Inventario Icon"> Inventario
        </a>
        <a href="../crud_Reportes/reportes.php" class="menu-item" data-section="reportes">
          <img src="../stockly_frontend/iconos/reporte.svg" alt="Reports Icon"> Reportes
        </a>
        <a href="../CRUD_Proveedores/get_proveedores.php" class="menu-item" data-section="proveedores">
          <img src="../stockly_frontend/iconos/proveedores.svg" alt="Suppliers Icon"> Proveedores
        </a>
        <a href="../crud_Ordenes/listar.php" class="menu-item" data-section="ordenes">
          <img src="../stockly_frontend/iconos/ordenes.svg" alt="Orders Icon"> Ordenes
        </a>
        <div class="menu-divider"></div>
        <a href="../crud_Ajustes/ajustes.php" class="menu-item" data-section="ajustes">
          <img src="../stockly_frontend/iconos/ajustes.svg" alt="Settings Icon"> Ajustes
        </a>
        <a href="logout.php" class="menu-item" data-section="cerrar" id="logout-btn">
          <img src="../stockly_frontend/iconos/cerrar sesion.svg" alt="Logout Icon"> Cerrar sesión
        </a> 
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
          logoutBtn.addEventListener('click', function(event) {
            event.preventDefault();
            localStorage.clear();
            sessionStorage.clear();
            window.location.href = '../CRUD_Usuarios/inicio_de_sesion.php';
          });
        }
      });
    </script>
  </div>
  <div class="main-content">
    <!-- Header Bar -->
    <div class="header-bar">
      <div class="search-container">
        <input class="search-input" placeholder="Buscar" type="text"/>
        <img alt="Buscar" class="search-icon" src="../stockly_frontend/iconos/buscar.svg"/>
      </div>
      <div class="user-profile">
        <div class="notification-icon">
          <img alt="Notifications" src="../stockly_frontend/iconos/notificacion.svg"/>
        </div>
        <img alt="Profile" class="profile-pic" src="../stockly_frontend/iconos/foto de perfil.png"/>
      </div>
    </div>
    <!-- Dashboard Grid -->
    <div class="dashboard-grid">
      <!-- Resumen de Ventas -->
      <div class="dashboard-card">
        <div class="card-header">
          <h2 class="card-title">Resumen de Ventas</h2>
          <a href="../crud_Reportes/reportes.php" class="card-action">Ver más</a>
        </div>
        <div class="stats-grid">
          <div class="stat-item">
            <img src="../stockly_frontend/iconos/ventas.svg" alt="Ventas Icon" class="stat-icon">
            <div class="stat-value"><?php echo number_format($ventas['total_ventas'] ?? 0); ?></div>
            <div class="stat-label">Ventas</div>
          </div>
          <div class="stat-item">
            <img src="../stockly_frontend/iconos/ingresos.svg" alt="Ingresos Icon" class="stat-icon">
            <div class="stat-value">₹ <?php echo number_format($ventas['total_ingresos'] ?? 0); ?></div>
            <div class="stat-label">Ingresos</div>
          </div>
          <div class="stat-item">
            <img src="../stockly_frontend/iconos/reporte.svg" alt="Beneficios Icon" class="stat-icon">
            <div class="stat-value">₹ <?php echo number_format($ventas['total_beneficios'] ?? 0); ?></div>
            <div class="stat-label">Beneficios</div>
          </div>
        </div>
      </div>
      <!-- Resumen de Inventario -->
      <div class="dashboard-card">
        <div class="card-header">
          <h2 class="card-title">Resumen de Inventario</h2>
          <a href="../crud_Productos/agregar_producto.php" class="card-action inventario-link">Ver más</a>
        </div>
        <div class="stats-grid inventory-grid">
          <div class="stat-item">
            <img src="../stockly_frontend/iconos/ordenes.svg" alt="Disponible Icon" class="stat-icon">
            <div class="stat-value"><?php echo number_format($inventario['cantidad_disponible'] ?? 0); ?></div>
            <div class="stat-label">Stock total disponible</div>
          </div>
        </div>
      </div>
      <!-- Resumen de Compras -->
      <div class="dashboard-card">
        <div class="card-header">
          <h2 class="card-title">Resumen de Compras</h2>
          <a href="../crud_Ordenes/listar.php" class="card-action">Ver más</a>
        </div>
        <div class="stats-grid">
          <div class="stat-item">
            <img src="../stockly_frontend/iconos/compras.svg" alt="Compras Icon" class="stat-icon">
            <div class="stat-value"><?php echo number_format($compras['total_compras'] ?? 0); ?></div>
            <div class="stat-label">Compras</div>
          </div>
        </div>
      </div>
      <!-- Resumen de Productos -->
      <div class="dashboard-card">
        <div class="card-header">
          <h2 class="card-title">Resumen de Productos</h2>
         <a href="../CRUD_Proveedores/get_proveedores.php" class="card-action">Ver más</a>
        </div>
        <div class="stats-grid products-grid">
          <div class="stat-item">
            <img src="../stockly_frontend/iconos/proveedores.svg" alt="Proveedores Icon" class="stat-icon">
            <div class="stat-value"><?php echo number_format($total_proveedores ?? 0); ?></div>
            <div class="stat-label">Número de Proveedores</div>
          </div>
          <div class="stat-item">
            <img src="../stockly_frontend/iconos/numero de categorias.svg" alt="Categorías Icon" class="stat-icon">
            <div class="stat-value"><?php echo number_format($total_categorias ?? 0); ?></div>
            <div class="stat-label">Número de Categorías</div>
          </div>
        </div>
      </div>
      <!-- Resumen de Ordenes -->
      <div class="dashboard-card">
        <div class="card-header">
          <h2 class="card-title">Resumen de Ordenes</h2>
          <a href="../crud_Ordenes/listar.php" class="card-action">Ver más</a>
        </div>
        <div class="stats-grid">
          <div class="stat-item">
            <img src="../stockly_frontend/iconos/ordenes.svg" alt="Órdenes Icon" class="stat-icon">
            <div class="stat-value"><?php echo number_format($total_ordenes ?? 0); ?></div>
            <div class="stat-label">Órdenes Totales</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../stockly_frontend/script-dashboard.js"></script>
</body>
</html>