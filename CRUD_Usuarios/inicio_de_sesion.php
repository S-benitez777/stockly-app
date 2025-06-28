<?php
// Iniciar sesión para manejo de mensajes
session_start();

// Verificar si hay mensajes de error o éxito
$mensaje_error = '';
$mensaje_exito = '';

if (isset($_SESSION['error'])) {
    $mensaje_error = $_SESSION['error'];
    unset($_SESSION['error']);
}

if (isset($_SESSION['exito'])) {
    $mensaje_exito = $_SESSION['exito'];
    unset($_SESSION['exito']);
}

// También verificar parámetros GET para compatibilidad
if (isset($_GET['error'])) {
    switch($_GET['error']) {
        case 'invalid_credentials':
            $mensaje_error = 'Usuario o contraseña incorrectos.';
            break;
        case 'empty_fields':
            $mensaje_error = 'Todos los campos son obligatorios.';
            break;
        case 'user_not_found':
            $mensaje_error = 'El usuario no existe.';
            break;
        case 'account_inactive':
            $mensaje_error = 'Su cuenta está inactiva. Contacte al administrador.';
            break;
        default:
            $mensaje_error = 'Error en el inicio de sesión. Inténtelo de nuevo.';
    }
}

if (isset($_GET['success'])) {
    switch($_GET['success']) {
        case 'registered':
            $mensaje_exito = 'Registro exitoso. Ya puede iniciar sesión.';
            break;
        case 'logout':
            $mensaje_exito = 'Sesión cerrada correctamente.';
            break;
        default:
            $mensaje_exito = 'Operación exitosa.';
    }
}

// Verificar si ya está logueado
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio de Sesión - Stock.ly</title>
  <link rel="stylesheet" href="../stockly_frontend/inicio de sesion.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>
<body>
  <!-- Barra de navegación superior -->
  <div class="navbar">
    <div class="navbar-background"></div>
    <div class="navbar-brand">STOCK.LY</div>
  </div>
    
  <div class="page-content">
    <!-- Sección principal con estructura de dos columnas -->
    <div class="main-container">
      <!-- Columna del logo grande -->
      <div class="logo-column">
        <div class="logo-container"></div>
        <div class="logo-text">STOCK.LY</div>
      </div>
          
      <!-- Columna del formulario -->
      <div class="form-column">
        <!-- Logo pequeño en el formulario -->
        <div class="form-logo"></div>
              
        <div class="form-header">
          <h1 class="form-title">Accede a tu cuenta</h1>
          <p class="form-subtitle">¡Bienvenido de nuevo! Ingrese sus datos, por favor</p>
          
          <!-- Mensajes de error y éxito -->
          <?php if ($mensaje_error): ?>
            <div id="mensaje-error" class="error-message" style="display: block;">
              <?php echo htmlspecialchars($mensaje_error); ?>
            </div>
          <?php endif; ?>
          
          <?php if ($mensaje_exito): ?>
            <div id="mensaje-exito" class="success-message" style="display: block;">
              <?php echo htmlspecialchars($mensaje_exito); ?>
            </div>
          <?php endif; ?>
        </div>
              
        <form class="inicio_de_sesion-form" id="inicio_de_sesionForm" method="POST" action="inicio_de_sesion_procesar.php">
          <div class="form-group">
            <label for="username" class="form-label">Usuario</label>
            <input type="text" id="username" name="username" class="form-control" 
                   placeholder="Ingrese su usuario" required minlength="3" maxlength="50"
                   value="<?php echo isset($_SESSION['form_data']['username']) ? htmlspecialchars($_SESSION['form_data']['username']) : ''; ?>">
          </div>
          
          <div class="form-group">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" 
                   placeholder="Ingrese su contraseña" required minlength="6" maxlength="255">
          </div>
          
          <div class="form-options">
            <div class="remember-me">
              <input type="checkbox" id="remember" name="remember" value="1">
              <label for="remember">Recordarme</label>
            </div>
            <a href="recuperar_password.php" class="forgot-password">¿Olvidó su contraseña?</a>
          </div>
          
          <button type="submit" class="btn-signin">Iniciar sesión</button>
          
          <div class="signup-option">
            <span class="signup-text">¿No tiene una cuenta?</span>
            <a href="registro.php" class="btn-signup">Registrarse</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('inicio_de_sesionForm').addEventListener('submit', function(e) {
      // Validaciones del lado del cliente
      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value;
      
      // Función para mostrar error
      function mostrarError(mensaje) {
        let errorDiv = document.getElementById('mensaje-error');
        if (!errorDiv) {
          errorDiv = document.createElement('div');
          errorDiv.id = 'mensaje-error';
          errorDiv.className = 'error-message';
          document.querySelector('.form-header').appendChild(errorDiv);
        }
        errorDiv.textContent = mensaje;
        errorDiv.style.display = 'block';
      }
      
      // Limpiar mensajes de error previos
      const errorDiv = document.getElementById('mensaje-error');
      if (errorDiv) errorDiv.style.display = 'none';
      
      // Validar que los campos no estén vacíos
      if (!username || !password) {
        mostrarError('Por favor, complete todos los campos.');
        e.preventDefault();
        return false;
      }
      
      // Validar longitud mínima
      if (username.length < 3) {
        mostrarError('El usuario debe tener al menos 3 caracteres.');
        e.preventDefault();
        return false;
      }
      
      if (password.length < 6) {
        mostrarError('La contraseña debe tener al menos 6 caracteres.');
        e.preventDefault();
        return false;
      }
      
      // Si todas las validaciones pasan, el formulario se enviará normalmente al PHP
      return true;
    });
  </script>
</body>
</html>
<?php
// Limpiar datos del formulario después de mostrarlos
if (isset($_SESSION['form_data'])) {
    unset($_SESSION['form_data']);
}
?>