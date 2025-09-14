const apiUrl = "http://localhost/FerreteriaUni/Backend/api_productos.php";

// 👉 función para listar productos
async function cargarProductos() {
  let res = await fetch(`${apiUrl}?accion=listar`);
  let data = await res.json();

  let cont = document.getElementById("productos");
  cont.innerHTML = "";

  data.forEach(p => {
    cont.innerHTML += `
      <div class="producto">
        <h3>${p.nombre_producto}</h3>
        <p>${p.Descripcion}</p>
        <p>Precio: $${p.Precio}</p>
        <p>Stock: ${p.Stock}</p>
        <button onclick="editarProducto(${p.id_productos})">✏️ Editar</button>
        <button onclick="eliminarProducto(${p.id_productos})">🗑 Eliminar</button>
      </div>
    `;
  });
}

// 👉 función para agregar producto
document.getElementById("btnAgregar").addEventListener("click", async () => {
  let nuevo = {
    nombre: document.getElementById("nombre").value,
    descripcion: document.getElementById("descripcion").value,
    precio: parseFloat(document.getElementById("precio").value),
    stock: parseInt(document.getElementById("stock").value),
    id_estado: 1,
    id_categoria: 1,
    id_proveedor: 2
  };

  let res = await fetch(`${apiUrl}?accion=agregar`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(nuevo)
  });

  let result = await res.json();
  if (result.success) {
    alert("Producto agregado ✅, ID: " + result.id);
    cargarProductos();
  } else {
    alert("Error: " + result.error);
  }
});

// 👉 función para editar producto
async function editarProducto(id) {
  let nuevoNombre = prompt("Nuevo nombre del producto:");
  let nuevoPrecio = prompt("Nuevo precio:");
  let nuevoStock = prompt("Nuevo stock:");

  if (!nuevoNombre || !nuevoPrecio || !nuevoStock) {
    alert("⚠️ Todos los campos son obligatorios");
    return;
  }

  let actualizado = {
    id_productos: id,
    nombre: nuevoNombre,
    descripcion: "Actualizado",
    precio: parseFloat(nuevoPrecio),
    stock: parseInt(nuevoStock),
    id_estado: 1,
    id_categoria: 1,
    id_proveedor: 2
  };

  let res = await fetch(`${apiUrl}?accion=editar`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(actualizado)
  });

  let result = await res.json();
  if (result.success) {
    alert("Producto actualizado ✅");
    cargarProductos();
  } else {
    alert("Error: " + result.error);
  }
}

// 👉 función para eliminar producto
async function eliminarProducto(id) {
  if (!confirm("¿Seguro que quieres eliminar este producto?")) return;

  let res = await fetch(`${apiUrl}?accion=eliminar`, {
    method: "DELETE",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id_productos: id })
  });

  let result = await res.json();
  if (result.success) {
    alert("Producto eliminado ✅");
    cargarProductos();
  } else {
    alert("Error: " + result.error);
  }
}
cargarProductos();