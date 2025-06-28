document.addEventListener('DOMContentLoaded', function () {
    // Cargar categorías y luego proveedores
    fetch('../crud_Productos/obtener_categorias.php')
        .then(res => {
            if (!res.ok) throw new Error('Error al obtener categorías');
            return res.json();
        })
        .then(categorias => {
            const select = document.getElementById('product-category');
            if (select && Array.isArray(categorias)) {
                categorias.forEach(cat => {
                    const option = document.createElement('option');
                    option.value = cat.id || cat.categoria_id;
                    option.textContent = cat.nombre;
                    select.appendChild(option);
                });
            }
            // Ahora cargar proveedores
            return fetch('../crud_Productos/obtener_proveedores.php');
        })
        .then(res => {
            if (!res.ok) throw new Error('Error al obtener proveedores');
            return res.json();
        })
        .then(proveedores => {
            const select = document.getElementById('product-supplier');
            if (select && Array.isArray(proveedores)) {
                proveedores.forEach(prov => {
                    const option = document.createElement('option');
                    option.value = prov.id || prov.proveedor_id;
                    option.textContent = prov.nombre || prov.nombre_comercial;
                    select.appendChild(option);
                });
            }
        })
        .catch(err => {
            console.error(err);
            alert('Ocurrió un error al cargar categorías o proveedores.');
        });

    // Cargar resumen de inventario
    fetch('../crud_Productos/resumen_inventario.php')
        .then(res => {
            if (!res.ok) throw new Error('Error al obtener resumen de inventario');
            return res.json();
        })
        .then(data => {
            if (data) {
                const setText = (selector, value) => {
                    const el = document.querySelector(selector);
                    if (el) el.textContent = value;
                };
                setText('.number.categorias', data.total_categorias ?? 0);
                setText('.number.productos', data.total_productos ?? 0);
                setText('.value.ingresos', '₹' + (data.total_ingresos ?? 0));
                setText('.number.top-vendidos', data.top_vendidos ?? 0);
                setText('.value.top-valor', '₹' + (data.valor_top_vendidos ?? 0));
                setText('.number.poco-disponible', data.poco_disponibles ?? 0);
                setText('.value.agotado', data.agotados ?? 0);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Ocurrió un error al cargar el resumen de inventario.');
        });

    // Modal y formulario
    const addProductModal = document.getElementById('add-product-modal');
    const addProductForm = document.getElementById('add-product-form');
    const addProductBtn = document.getElementById('add-product-btn');
    const cancelBtn = document.getElementById('cancel-btn');

    if (addProductBtn && addProductModal) {
        addProductBtn.addEventListener('click', () => addProductModal.style.display = 'flex');
    }

    if (cancelBtn && addProductModal) {
        cancelBtn.addEventListener('click', () => addProductModal.style.display = 'none');
    }

    if (addProductModal) {
        window.addEventListener('click', (event) => {
            if (event.target === addProductModal) {
                addProductModal.style.display = 'none';
            }
        });
    }

    if (addProductForm) {
        addProductForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Valida que todos los campos existen antes de usarlos
            const nombre = document.getElementById('product-name')?.value || '';
            const sku = document.getElementById('product-id')?.value || '';
            const categoria_id = document.getElementById('product-category')?.value || '';
            const proveedor_id = document.getElementById('product-supplier')?.value || '';
            const costo_promedio = document.getElementById('product-price')?.value || '';
            const stock = document.getElementById('product-quantity')?.value || '';

            // Validación básica
            if (!nombre || !sku || !categoria_id || !proveedor_id || !costo_promedio || !stock) {
                alert('Por favor, completa todos los campos.');
                return;
            }

            // Envía los datos como JSON (recomendado)
            fetch('../crud_Productos/agregar_productos.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    nombre,
                    sku,
                    categoria_id,
                    proveedor_id,
                    costo_promedio,
                    stock,
                    es_perecedero: 1,
                    creado_por: 1
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Producto agregado correctamente');
                        location.reload();
                    } else {
                        alert(data.error || 'Error al agregar producto');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error de conexión al agregar producto');
                });
        });
    }
});
