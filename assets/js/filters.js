document.addEventListener('DOMContentLoaded', function() {
  // Selector de contenedor de productos
  const grid = document.querySelector('.grid-products');
  if (!grid) return;

  // Función para lanzar la petición AJAX
  function fetchFilteredProducts() {
    // Recolectar filtros marcados
    const checkboxes = Array.from(document.querySelectorAll('.ajax-filter:checked'));
    const formData = new FormData();
    formData.append('action', 'ctm_filter_products');
    formData.append('nonce', ctm_ajax.nonce);

    checkboxes.forEach(cb => {
      const tax = cb.dataset.tax;
      const term = cb.dataset.term;
      // Si ya hay array, lo añade; si no, crea
      if (!formData.has(tax + '[]')) {
        formData.append(tax + '[]', term);
      } else {
        formData.append(tax + '[]', term);
      }
    });

    fetch(ctm_ajax.ajax_url, {
      method: 'POST',
      credentials: 'same-origin',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        grid.innerHTML = data.data.html;
      }
    })
    .catch(console.error);
  }

  // Atachar listener a todos los checkboxes que existan
  document.querySelectorAll('.ajax-filter').forEach(cb => {
    cb.addEventListener('change', fetchFilteredProducts);
  });
});
