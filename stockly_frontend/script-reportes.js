document.addEventListener('DOMContentLoaded', () => {
    cargarReportes();
});

function cargarReportes() {
    fetch('../crud_Reportes/reportes.php?action=listar')
        .then(res => res.json())
        .then(data => renderTabla(data));
}

function renderTabla(data) {
    const tabla = document.querySelector('.tabla-reportes tbody');
    tabla.innerHTML = '';
    data.forEach(rep => {
        tabla.innerHTML += `
        <tr>
            <td>${rep.id_reporte}</td>
            <td>${rep.tipo_reporte}</td>
            <td>${rep.ventas_totales}</td>
            <td>${rep.cantidad_ventas}</td>
            <td>${rep.mejor_vendido}</td>
            <td>${rep.menos_vendido}</td>
            <td>
                <button onclick="editarReporte(${rep.id_reporte})">Editar</button>
                <button onclick="eliminarReporte(${rep.id_reporte})">Eliminar</button>
            </td>
        </tr>`;
    });
}

function abrirModal(reporte = null) {
    document.getElementById('form-reporte').reset();
    document.getElementById('modal-reporte').classList.remove('hidden');
    document.getElementById('modal-titulo').textContent = reporte ? 'Editar Reporte' : 'Nuevo Reporte';

        if (reporte) {
            document.getElementById('id_reporte').value = reporte.id_reporte;
            document.getElementById('tipo_reporte').value = reporte.tipo_reporte;
            document.getElementById('ventas_totales').value = reporte.ventas_totales;
            document.getElementById('cantidad_ventas').value = reporte.cantidad_ventas;
            document.getElementById('mejor_vendido').value = reporte.mejor_vendido;
        }
    }
// Cierra el modal
function cerrarModal() {
    document.getElementById('modal-reporte').classList.add('hidden');
}

// Maneja el submit del formulario del modal
document.addEventListener('DOMContentLoaded', () => {
    // ...ya tienes cargarReportes aquí...
    const form = document.getElementById('form-reporte');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            // Aquí deberías agregar la lógica para guardar o editar el reporte
            // Por ejemplo, puedes hacer un fetch POST a reportes.php
            cerrarModal();
            cargarReportes(); // Recarga la tabla después de guardar
        });
    }
});
