console.log("✅ JS cargado correctamente");

document.addEventListener('DOMContentLoaded', () => {
  const proveedoresList = document.getElementById('proveedores-list');
  const modal = document.getElementById('modal-agregar-proveedor');
  const btnAgregar = document.getElementById('btn-agregar-proveedor');
  const btnGuardar = document.getElementById('btn-guardar');
  const btnCancelar = document.querySelector('.btn-cancel');

  // Campos del formulario
  const campos = {
    proveedor_id: document.getElementById('proveedor_id'),
    nombre_legal: document.getElementById('nombre_legal'),
    nombre_comercial: document.getElementById('nombre_comercial'),
    rfc: document.getElementById('rfc'),
    direccion: document.getElementById('direccion'),
    telefono: document.getElementById('telefono'),
    email_contacto: document.getElementById('email_contacto'),
    dias_credito: document.getElementById('dias_credito'),
    limite_credito: document.getElementById('limite_credito'),
    esta_activo: document.getElementById('esta_activo'),
  };

  // Mostrar modal vacío
  btnAgregar.addEventListener('click', () => {
    Object.values(campos).forEach(input => input.value = '');
    modal.classList.add('show');
  });

  // Cancelar modal
  btnCancelar.addEventListener('click', () => {
    modal.classList.remove('show');
  });

  // Guardar (crear o actualizar)
  btnGuardar.addEventListener('click', async () => {
    const datos = {};
    Object.keys(campos).forEach(k => datos[k] = campos[k].value);

    const esNuevo = datos.proveedor_id === '';
    const url = esNuevo ? 'add_proveedor.php' : 'update_proveedor.php';

    try {
      const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos),
      });

      if (res.ok) {
        modal.classList.remove('show');
        cargarProveedores();
      } else {
        alert('Error al guardar');
      }
    } catch (error) {
      alert('Error en la conexión');
    }
  });

  // Cargar y renderizar proveedores
  async function cargarProveedores() {
    try {
      const res = await fetch('get_proveedores.php');
      const datos = await res.json();
      proveedoresList.innerHTML = '';

      datos.forEach(p => {
        const row = document.createElement('div');
        row.className = 'proveedor-row';
        row.innerHTML = `
          <div>${p.nombre_legal}</div>
          <div>${p.nombre_comercial}</div>
          <div>${p.telefono}</div>
          <div>${p.email_contacto}</div>
          <div>${p.dias_credito} días</div>
          <div>
            <button class="editar-btn" data-id="${p.proveedor_id}">✏️</button>
            <button class="eliminar-btn" data-id="${p.proveedor_id}">🗑️</button>
          </div>
        `;
        proveedoresList.appendChild(row);
      });

      document.querySelectorAll('.editar-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
          const res = await fetch(`get_proveedor_by_id.php?id=${btn.dataset.id}`);
          const data = await res.json();
          Object.keys(campos).forEach(k => campos[k].value = data[k] ?? '');
          modal.classList.add('show');
        });
      });

      document.querySelectorAll('.eliminar-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
          if (confirm('¿Eliminar este proveedor?')) {
            await fetch(`delete_proveedor.php?id=${btn.dataset.id}`);
            cargarProveedores();
          }
        });
      });

    } catch (error) {
      console.error('Error al cargar proveedores:', error);
    }
  }

  cargarProveedores();
});

