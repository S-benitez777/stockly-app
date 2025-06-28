document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formCrearUsuario');
    if (!form) return;
    form.addEventListener('submit', (e) => {
        const nombre = form.nombre.value.trim();
        const correo = form.correo.value.trim();
        const password = form.password.value.trim();
        let errores = [];
        if (nombre === '') errores.push("El nombre no puede estar vacío.");
        const correoRegex = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
        if (!correoRegex.test(correo)) errores.push("Correo no válido.");
        if (password.length < 6) errores.push("La contraseña debe tener al menos 6 caracteres.");
        if (errores.length > 0) {
            e.preventDefault();
            alert(errores.join("\n"));
        }
    });
});