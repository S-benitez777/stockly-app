// DOM Elements
const tableContent = document.querySelector('.table-content');
const addOrderBtn = document.getElementById('addOrderBtn');
const modal = document.getElementById('newOrderModal');
const cancelOrderBtn = document.getElementById('cancelOrderBtn');
const newOrderForm = document.getElementById('newOrderForm');
const statusFilter = document.querySelector('.status-filter');
const prevPageBtn = document.querySelector('.prev-btn');
const nextPageBtn = document.querySelector('.next-btn');
const paginationInfo = document.querySelector('.pagination-info');

let currentPage = 1;
let itemsPerPage = 10;
let ordenes = [];
let allOrdenes = []; // Para mantener la lista original

async function fetchOrdenes() {
  try {
    const res = await fetch('../crud_Ordenes/listar.php', { method: 'GET' });
    if (!res.ok) throw new Error('Error al cargar órdenes');
    ordenes = await res.json();
    allOrdenes = [...ordenes];
    currentPage = 1;
    renderOrders();
  } catch (err) {
    tableContent.innerHTML = `<div class="no-data">${err.message}</div>`;
  }
}

function renderOrders() {
  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = Math.min(startIndex + itemsPerPage, ordenes.length);
  const ordenesPagina = ordenes.slice(startIndex, endIndex);

  tableContent.innerHTML = '';

  if (ordenesPagina.length === 0) {
    tableContent.innerHTML = '<div class="no-data">No hay órdenes que mostrar</div>';
    updatePagination();
    return;
  }

  ordenesPagina.forEach(order => {
    const row = document.createElement('div');
    row.className = 'table-row';
    row.innerHTML = `
      <div class="cell">${order.nombre_producto}</div>
      <div class="cell">₹${order.valor_orden}</div>
      <div class="cell">${order.cantidad} ${order.unidad}</div>
      <div class="cell">${order.producto_id}</div>
      <div class="cell">${order.fecha_entrega}</div>
      <div class="cell"><span class="status-cell ${order.estado}">${getStatusText(order.estado)}</span></div>
      <div class="cell">
        <button onclick="editarOrden(${order.orden_id})">✏️</button>
        <button onclick="eliminarOrden(${order.orden_id})">🗑️</button>
      </div>
    `;
    tableContent.appendChild(row);
  });

  updatePagination();
}

function getStatusText(status) {
  switch (status) {
    case 'confirmado': return 'Confirmado';
    case 'retrasado': return 'Retrasado';
    case 'en-entrega': return 'En entrega';
    case 'regresado': return 'Regresado';
    default: return status;
  }
}

function updatePagination() {
  const totalPages = Math.max(1, Math.ceil(ordenes.length / itemsPerPage));
  paginationInfo.textContent = `Página ${currentPage} de ${totalPages}`;
  prevPageBtn.disabled = currentPage === 1;
  nextPageBtn.disabled = currentPage === totalPages;
}

// ------------------------ CRUD ------------------------

async function cargarCategorias() {
  try {
    const res = await fetch('ordenes/categorias.php');
    if (!res.ok) throw new Error('Error al cargar categorías');
    const categorias = await res.json();
    const select = document.getElementById('category');
    select.innerHTML = '<option value="">Seleccione categoría</option>';
    categorias.forEach(c => {
      const opt = document.createElement('option');
      opt.value = c;
      opt.textContent = c;
      select.appendChild(opt);
    });
  } catch (err) {
    alert(err.message);
  }
}

function showModal() {
  modal.style.display = 'block';
  newOrderForm.reset();
  cargarCategorias();
  // Restaurar el evento submit original
  newOrderForm.onsubmit = addNewOrder;
}

function hideModal() {
  modal.style.display = 'none';
  newOrderForm.reset();
  newOrderForm.onsubmit = addNewOrder;
}

async function addNewOrder(e) {
  e.preventDefault();

  const data = {
    nombre_producto: document.getElementById('productName').value,
    producto_id: document.getElementById('productId').value,
    categoria: document.getElementById('category').value,
    valor_orden: parseFloat(document.getElementById('orderValue').value),
    cantidad: parseInt(document.getElementById('quantity').value),
    unidad: document.getElementById('unit').value,
    precio_compra: parseFloat(document.getElementById('purchasePrice').value),
    fecha_entrega: document.getElementById('deliveryDate').value,
    estado: 'confirmado',
    usuario_id: 1, // ID fijo de prueba
    producto_db_id: 1
  };

  try {
    const res = await fetch('ordenes/agregar.php', {
      method: 'POST',
      body: JSON.stringify(data)
    });
    const result = await res.json();
    if (result.success) {
      hideModal();
      fetchOrdenes();
    } else {
      alert('Error al guardar: ' + result.error);
    }
  } catch (err) {
    alert('Error de red al guardar');
  }
}

async function eliminarOrden(id) {
  if (!confirm("¿Eliminar esta orden?")) return;
  try {
    const res = await fetch('ordenes/eliminar.php', {
      method: 'DELETE',
      body: JSON.stringify({ orden_id: id })
    });
    const result = await res.json();
    if (result.success) {
      fetchOrdenes();
    } else {
      alert('Error al eliminar: ' + result.error);
    }
  } catch (err) {
    alert('Error de red al eliminar');
  }
}

async function editarOrden(id) {
  const orden = ordenes.find(o => o.orden_id == id);
  if (!orden) return alert("Orden no encontrada");

  showModal();

  document.getElementById('productName').value = orden.nombre_producto;
  document.getElementById('productId').value = orden.producto_id;
  document.getElementById('category').value = orden.categoria;
  document.getElementById('orderValue').value = orden.valor_orden;
  document.getElementById('quantity').value = orden.cantidad;
  document.getElementById('unit').value = orden.unidad;
  document.getElementById('purchasePrice').value = orden.precio_compra;
  document.getElementById('deliveryDate').value = orden.fecha_entrega;

  newOrderForm.onsubmit = async function (e) {
    e.preventDefault();
    const data = {
      orden_id: id,
      nombre_producto: document.getElementById('productName').value,
      producto_id: document.getElementById('productId').value,
      categoria: document.getElementById('category').value,
      valor_orden: parseFloat(document.getElementById('orderValue').value),
      cantidad: parseInt(document.getElementById('quantity').value),
      unidad: document.getElementById('unit').value,
      precio_compra: parseFloat(document.getElementById('purchasePrice').value),
      fecha_entrega: document.getElementById('deliveryDate').value,
      estado: orden.estado,
      usuario_id: orden.usuario_id,
      producto_db_id: orden.producto_db_id
    };

    try {
      const res = await fetch('ordenes/editar.php', {
        method: 'PUT',
        body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        hideModal();
        fetchOrdenes();
      } else {
        alert('Error al editar: ' + result.error);
      }
    } catch (err) {
      alert('Error de red al editar');
    }
  };
}

// ------------------------ Listeners ------------------------

document.addEventListener('DOMContentLoaded', () => {
  fetchOrdenes();

  addOrderBtn.addEventListener('click', showModal);
  cancelOrderBtn.addEventListener('click', hideModal);

  statusFilter.addEventListener('change', () => {
    const filtro = statusFilter.value;
    if (filtro === "") {
      ordenes = [...allOrdenes];
    } else {
      ordenes = allOrdenes.filter(o => o.estado === filtro);
    }
    currentPage = 1;
    renderOrders();
  });

  prevPageBtn.addEventListener('click', () => {
    if (currentPage > 1) {
      currentPage--;
      renderOrders();
    }
  });

  nextPageBtn.addEventListener('click', () => {
    const totalPages = Math.ceil(ordenes.length / itemsPerPage);
    if (currentPage < totalPages) {
      currentPage++;
      renderOrders();
    }
  });

  window.addEventListener('click', e => {
    if (e.target === modal) hideModal();
  });

  newOrderForm.onsubmit = addNewOrder;
});
