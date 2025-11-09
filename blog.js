// 📦 Cargar productos del JSON
async function cargarProductos() {
  try {
    const res = await fetch('productos.json');
    const productos = await res.json();

    const contenedor = document.getElementById('productos');
    const input = document.getElementById('searchInput');
    const limpiar = document.getElementById('clearBtn');
    const contador = document.getElementById('contador');

    // 🔹 Renderizar productos
    function mostrarProductos(lista) {
      contenedor.innerHTML = '';
      if (lista.length === 0) {
        contenedor.innerHTML = `<p class="text-center col-span-full text-gray-600">No se encontraron productos.</p>`;
        contador.textContent = 'Mostrando 0 productos';
        return;
      }

      lista.forEach(prod => {
        const div = document.createElement('div');
        div.className = 'bg-white rounded-xl p-5 shadow hover:shadow-lg transition w-full max-w-xs text-center';
        div.innerHTML = `
          <img src="${prod.imagen}" alt="${prod.nombre}" class="rounded-lg mb-3 w-full h-48 object-cover mx-auto">
          <h3 class="font-bold text-lg text-[var(--blue)] mb-1">${prod.nombre}</h3>
          <p class="text-gray-900 font-semibold mb-2">Desde $${prod.precio} MXN</p>
          <a href="https://wa.me/5217571341959?text=${encodeURIComponent(prod.whatsapp)}"
             target="_blank"
             class="inline-flex items-center gap-2 bg-[#25D366] text-white px-4 py-2 rounded-md hover:bg-green-600 transition">
             💬 Cotizar
          </a>
        `;
        contenedor.appendChild(div);
      });

      contador.textContent = `Mostrando ${lista.length} productos`;
    }

    // 🔍 Filtro en tiempo real
    function filtrar() {
      const texto = input.value.toLowerCase();
      const filtrados = productos.filter(p =>
        p.nombre.toLowerCase().includes(texto) ||
        p.categoria.toLowerCase().includes(texto)
      );
      mostrarProductos(filtrados);
    }

    // 🧹 Limpiar búsqueda
    limpiar.addEventListener('click', () => {
      input.value = '';
      mostrarProductos(productos);
    });

    input.addEventListener('input', filtrar);

    // Mostrar todo al inicio
    mostrarProductos(productos);
  } catch (err) {
    console.error('Error al cargar productos:', err);
  }
}

cargarProductos();
