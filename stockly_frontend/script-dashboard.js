// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
  // ===============================
  // Manejo de la barra lateral
  // ===============================
  const menuItems = document.querySelectorAll('.menu-item');

  // Función para activar un elemento del menú
  function activateMenuItem(item) {
    // Remover clase active de todos los ítems
    menuItems.forEach(menuItem => {
      menuItem.classList.remove('active');
    });
    
    // Agregar clase active al ítem seleccionado
    item.classList.add('active');
  }

  // Agregar evento click a todos los ítems del menú
  menuItems.forEach(item => {
    item.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      
      // Solo prevenir el comportamiento predeterminado si es '#'
      if (href === '#') {
        e.preventDefault();
        alert('Esta sección está en desarrollo');
      }
      
      activateMenuItem(this);
      
      // Almacenar la sección activa en sessionStorage para persistencia entre páginas
      const section = this.getAttribute('data-section');
      sessionStorage.setItem('activeSection', section);
      console.log(`Sección seleccionada: ${section}`);
    });
  });
  
  // Verificar si hay una sección activa guardada en sessionStorage
  const activeSection = sessionStorage.getItem('activeSection');
  if (activeSection) {
    const activeItem = document.querySelector(`.menu-item[data-section="${activeSection}"]`);
    if (activeItem) {
      activateMenuItem(activeItem);
    }
  }

  // ===============================
  // Chart.js - Gráfico de Ventas y Compras
  // ===============================
  const ventasComprasCtx = document.getElementById('ventas-compras-chart');
  
  // Verificar si el elemento existe antes de crear el gráfico
  if (ventasComprasCtx) {
    const ctx = ventasComprasCtx.getContext('2d');
    
    // Datos semanales
    const datosSemanales = {
      labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct'],
      compras: [48000, 40000, 50000, 35000, 38000, 40000, 50000, 38000, 40000, 35000],
      ventas: [42000, 45000, 35000, 38000, 43000, 38000, 35000, 40000, 38000, 40000]
    };
    
    // Datos mensuales (simplemente para demostración)
    const datosMensuales = {
      labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct'],
      compras: [150000, 140000, 160000, 130000, 140000, 145000, 155000, 140000, 145000, 130000],
      ventas: [140000, 150000, 130000, 135000, 150000, 130000, 125000, 145000, 135000, 140000]
    };
    
    // Crear gráfico de ventas y compras
    let ventasComprasChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: datosSemanales.labels,
        datasets: [
          {
            label: 'Compras',
            data: datosSemanales.compras,
            backgroundColor: '#4d89ff',
            borderColor: '#4d89ff',
            borderWidth: 0,
            borderRadius: 4,
            barPercentage: 0.6,
            categoryPercentage: 0.7
          },
          {
            label: 'Ventas',
            data: datosSemanales.ventas,
            backgroundColor: '#73d13d',
            borderColor: '#73d13d',
            borderWidth: 0,
            borderRadius: 4,
            barPercentage: 0.6,
            categoryPercentage: 0.7
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            mode: 'index',
            intersect: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              drawBorder: false,
              color: '#f0f0f0'
            },
            ticks: {
              callback: function(value) {
                return value >= 1000 ? value / 1000 + 'k' : value;
              }
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });
  
    // Manejo de cambio entre semanal y mensual
    const chartToggleButtons = document.querySelectorAll('.chart-toggle');
    chartToggleButtons.forEach(button => {
      button.addEventListener('click', function() {
        chartToggleButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        
        const period = this.getAttribute('data-period');
        
        if (period === 'semanal') {
          updateChart(ventasComprasChart, datosSemanales);
        } else if (period === 'mensual') {
          updateChart(ventasComprasChart, datosMensuales);
        }
      });
    });
  
    function updateChart(chart, datos) {
      chart.data.labels = datos.labels;
      chart.data.datasets[0].data = datos.compras;
      chart.data.datasets[1].data = datos.ventas;
      chart.update();
    }
  }

  // ===============================
  // Chart.js - Gráfico de Ordenes
  // ===============================
  const ordenesCtx = document.getElementById('ordenes-chart');
  
  // Verificar si el elemento existe antes de crear el gráfico
  if (ordenesCtx) {
    const ctx = ordenesCtx.getContext('2d');
    
    // Crear gráfico de líneas para órdenes
    const ordenesChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May'],
        datasets: [
          {
            label: 'Ordenado',
            data: [1000, 3000, 2000, 3500, 2500],
            borderColor: '#ff6b6b',
            backgroundColor: 'rgba(255, 107, 107, 0.1)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#ff6b6b'
          },
          {
            label: 'Entregado',
            data: [500, 2500, 3500, 2000, 3000],
            borderColor: '#4eccb0',
            backgroundColor: 'rgba(78, 204, 176, 0.1)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#4eccb0'
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            mode: 'index',
            intersect: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              drawBorder: false,
              color: '#f0f0f0'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });
  }

  // ===============================
  // Botones de "Bajo" para productos con bajo stock
  // ===============================
  const stockButtons = document.querySelectorAll('.stock-action');
  stockButtons.forEach(button => {
    button.addEventListener('click', function() {
      // Simular una acción - en una implementación real esto podría
      // abrir un modal para reordenar o mostrar más opciones
      const productName = this.parentElement.querySelector('.product-name').textContent;
      alert(`Vas a reordenar más unidades de ${productName}`);
    });
  });
  
  // ===============================
  // Manejo de los enlaces "Ver más"
  // ===============================
  const inventarioLinks = document.querySelectorAll('.inventario-link');
  inventarioLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      // No es necesario preventDefault si queremos que navegue a la página de producto
      // Pero podemos activar el ítem correspondiente en la barra lateral
      const inventarioItem = document.querySelector('.menu-item[data-section="inventario"]');
      if (inventarioItem) {
        activateMenuItem(inventarioItem);
        sessionStorage.setItem('activeSection', 'inventario');
      }
    });
  });
});