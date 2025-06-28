// Script de navegación para la aplicación STOCK.LY
// Este script maneja la navegación entre las diferentes páginas del sistema

document.addEventListener('DOMContentLoaded', function() {
    // Referencias a los elementos de navegación en la barra lateral
    const menuItems = document.querySelectorAll('.menu-item, .sidebar li');
    
    // Configurar los manejadores de eventos para cada elemento del menú
    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Eliminar la clase 'active' de todos los elementos
            menuItems.forEach(mi => mi.classList.remove('active'));
            
            // Agregar la clase 'active' al elemento actual
            this.classList.add('active');
            
            // Obtener el ID del elemento o el atributo data-section
            const section = this.getAttribute('data-section') || this.id.replace('-item', '');
            
            // Manejar la navegación basada en la sección
            switch(section) {
                case 'menu':
                    window.location.href = 'dashboard.html';
                    break;
                case 'inventory':
                case 'inventario':
                    window.location.href = 'producto.html';
                    break;
                case 'reports':
                case 'reportes':
                    // Implementar navegación a reportes
                    console.log('reportes.html');
                    break;
                case 'suppliers':
                case 'proveedores':
                    // Implementar navegación a proveedores
                    console.log('Navegando a proveedores');
                    break;
                case 'orders':
                case 'ordenes':
                    // Implementar navegación a órdenes
                    console.log('Navegando a órdenes');
                    break;
                case 'settings':
                case 'ajustes':
                    // Implementar navegación a ajustes
                    console.log('Navegando a ajustes');
                    break;
                case 'logout':
                case 'cerrar':
                    // Implementar cierre de sesión
                    console.log('Cerrando sesión');
                    break;
                default:
                    console.log('Sección no reconocida:', section);
            }
        });
    });
    
    // Detectar la página actual y marcar el elemento correspondiente como activo
    const currentPage = window.location.pathname;
    if (currentPage.includes('dashboard') || currentPage === '/' || currentPage.endsWith('/')) {
        document.querySelector('[data-section="menu"], #menu-item').classList.add('active');
    } else if (currentPage.includes('inventario') || currentPage.includes('producto')) {
        document.querySelector('[data-section="inventario"], #inventory-item').classList.add('active');
    } else if (currentPage.includes('reportes')) {
        document.querySelector('[data-section="reportes"], #reports-item').classList.add('active');
    } else if (currentPage.includes('proveedores')) {
        document.querySelector('[data-section="proveedores"], #suppliers-item').classList.add('active');
    } else if (currentPage.includes('ordenes')) {
        document.querySelector('[data-section="ordenes"], #orders-item').classList.add('active');
    } else if (currentPage.includes('ajustes')) {
        document.querySelector('[data-section="ajustes"], #settings-item').classList.add('active');
    }
});

// Función para manejar búsquedas entre páginas
function handleSearch(query) {
    // Implementar lógica de búsqueda global que funcione en todas las páginas
    console.log('Buscando:', query);
    
    // Esta función podría redirigir a la página apropiada o mostrar resultados
    // según el tipo de búsqueda
}

// Exportar funciones útiles que se pueden usar en otros scripts
window.stockly = {
    navigate: function(page) {
        window.location.href = page + '.html';
    },
    search: handleSearch
};