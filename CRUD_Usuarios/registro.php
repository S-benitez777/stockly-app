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
        case 'user_exists':
            $mensaje_error = 'El usuario ya existe.';
            break;
        case 'email_exists':
            $mensaje_error = 'El correo electrónico ya está registrado.';
            break;
        case 'password_weak':
            $mensaje_error = 'La contraseña no cumple con los requisitos mínimos.';
            break;
        case 'database_error':
            $mensaje_error = 'Error en la base de datos. Inténtelo más tarde.';
            break;
        default:
            $mensaje_error = 'Error en el registro. Inténtelo de nuevo.';
    }
}

if (isset($_GET['success'])) {
    $mensaje_exito = 'Registro exitoso. Ya puede iniciar sesión.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro - Stock.ly</title>
  <link rel="stylesheet" href="../frontend/css/registro.css">
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
          <h1 class="form-title">Crea una cuenta</h1>
          <p class="form-subtitle">Ingresa tus datos para comenzar</p>
          
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
        
        <form class="register-form" id="registerForm" method="POST" action="procesar_registro.php">
          <div class="form-group">
            <label for="username" class="form-label">Usuario*</label>
            <input type="text" id="username" name="username" class="form-control" 
                   placeholder="Ingrese su usuario" required minlength="3" maxlength="50"
                   value="<?php echo isset($_SESSION['form_data']['username']) ? htmlspecialchars($_SESSION['form_data']['username']) : ''; ?>">
          </div>
          
          <div class="form-group">
            <label for="nombre_completo" class="form-label">Nombre completo*</label>
            <input type="text" id="nombre_completo" name="nombre_completo" class="form-control" 
                   placeholder="Ingrese su nombre" required minlength="2" maxlength="100"
                   value="<?php echo isset($_SESSION['form_data']['nombre_completo']) ? htmlspecialchars($_SESSION['form_data']['nombre_completo']) : ''; ?>">
          </div>
          
          <div class="form-group">
            <label for="email" class="form-label">Correo*</label>
            <input type="email" id="email" name="email" class="form-control" 
                   placeholder="Ingrese su correo" required maxlength="100"
                   value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>">
          </div>
          
          <div class="form-group">
            <label for="password" class="form-label">Contraseña*</label>
            <input type="password" id="password" name="password" class="form-control" 
                   placeholder="Crear una contraseña" required minlength="6" maxlength="255">
            <span class="password-hint">Debe tener al menos 6 caracteres.</span>
          </div>
          
          <button type="submit" class="btn-register">Crear cuenta</button>
          
          <div class="signin-option">
            <span class="signin-text">¿Ya tiene una cuenta?</span>
            <a href="inicio_de_sesion.php" class="btn-signin">Acceda</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      // Validaciones del lado del cliente
      const username = document.getElementById('username').value.trim();
      const nombreCompleto = document.getElementById('nombre_completo').value.trim();
      const email = document.getElementById('email').value.trim();
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
      
      // Limpiar mensajes previos
      const errorDiv = document.getElementById('mensaje-error');
      if (errorDiv) errorDiv.style.display = 'none';
      
      // Validar que los campos no estén vacíos
      if (!username || !nombreCompleto || !email || !password) {
        mostrarError('Por favor, complete todos los campos obligatorios.');
        e.preventDefault();
        return false;
      }
      
      // Validar longitud de contraseña
      if (password.length < 6) {
        mostrarError('La contraseña debe tener al menos 6 caracteres.');
        e.preventDefault();
        return false;
      }
      
      // Validar formato de email
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        mostrarError('Por favor, ingrese un correo electrónico válido.');
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