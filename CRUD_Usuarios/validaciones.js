document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    const nombre = form.querySelector('[name="nombre"]');
    const correo = form.querySelector('[name="correo"]');
    const password = form.querySelector('[name="password"]');

    let errores = [];

    if (nombre && nombre.value.trim() === '') errores.push("El nombre es obligatorio.");
    if (correo && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(correo.value.trim())) errores.push("Correo inválido.");
    if (password && password.value.length < 6) errores.push("La contraseña debe tener al menos 6 caracteres.");

    if (errores.length > 0) {
      e.preventDefault();
      alert(errores.join("\n"));
    } else {
      e.preventDefault();
      window.location.href = 'inicio_de_sesion.html';
    }
  });
});