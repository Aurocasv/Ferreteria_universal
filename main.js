// Mostrar productos
async function cargarProductos() {
  let res = await fetch("api/productos.php?accion=listar");
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
      </div>
    `;
  });
}

// Agregar producto
document.getElementById("btnAgregar").addEventListener("click", async () => {
  let nuevo = {
    nombre: document.getElementById("nombre").value,
    descripcion: document.getElementById("descripcion").value,
    precio: document.getElementById("precio").value,
    stock: document.getElementById("stock").value
  };

  let res = await fetch("api/productos.php?accion=agregar", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(nuevo)
  });

  let result = await res.json();
  console.log("Respuesta del servidor:", result); // 👈 Aquí vemos qué devuelve PHP

  if (result.status === "ok") {
    alert("Producto agregado ✅");
    cargarProductos();
  } else {
    alert("Error: " + result.msg);
  }
});