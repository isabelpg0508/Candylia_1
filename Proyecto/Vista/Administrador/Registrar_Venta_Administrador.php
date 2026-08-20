<?php 
    session_start();
    $tipousuario = isset($_SESSION['TipoUsuario']) ? $_SESSION['TipoUsuario'] : null;
    include "../../Modelo/Inventario.php";
    $inventario = new inventario();
    $productos = $inventario->Consultar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet"/>
    <link src="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css" rel="stylesheet"/> 
    <link rel="stylesheet" href="Diseño_Registrar_Venta.css">
    <title>Registrar Ventas</title>
</head>
<body>
    <header>
        <h1>Registrar Ventas</h1>
        <?php if($tipousuario=='Administrador'): ?>
        <a href="Crud_Administrador_Venta.html"><button class="regresar"><i class="fa-solid fa-arrow-left"></i></button></a>
        <?php else:?>
        <a href='../Cajero/Crud_Cajero_Venta.html'><button class='regresar'><i class='fa-solid fa-arrow-left'></i></button></a>
        <?php endif; ?>
    </header>

    <form  method="POST" action="../../Controlador/LogicaVenta.php" class="contenedor-principal">
            <div class="columna-izquierda">
                <div class="tabla-contenedor">
                    <div class="tabla-header">
                        <h2>Selecciona los productos</h2>
                        <label>Documento del Cliente:</label>
                    <input type="number" name="DocumentoIdentidad" required value="1">
                    <input type="hidden" name="productosJson" id="productosJson" value="">

                    </div>
                    <input type="hidden" name="IdUsuario" required value="<?= $_SESSION['IdUsuario'] ?? '' ?>">

                    <table id='tabla-venta'>
                        <thead>
                            <tr>
                            <th></th>
                            <th>Nombre Producto</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Descripción</th>
                            <th>Cantidad a vender</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $p){ ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="productosSeleccionados[]" value="<?php echo $p['IdInventario'] ?>" class="checkProducto" onchange="checkProductoChange(this)">
                                    </td>
                                    <td><?php echo htmlspecialchars($p['Nombre']) ?></td>
                                    <td><!-- precio visible al usuario (formateado) -->
                                        $<?= number_format($p['Precio'], 0, ',', '.') ?>
                                        <!-- precio real enviado al servidor (sin formato) -->
                                        <input type="hidden" name="PrecioProducto[<?php echo $p['IdInventario']; ?>]" value="<?php echo $p['Precio']; ?>" id="precioProducto<?php echo $p['IdInventario']; ?>" >
                                    </td>
                                    <td><?php echo $p['Stock']; ?></td>
                                    <td><?php echo htmlspecialchars($p['Descripcion']) ?></td>
                                    <td>
                                        <input type="number" name="CantidadVendida[<?php echo $p['IdInventario']; ?>]" min="1" max="<?php echo $p['Stock']; ?>" value="1" <?php echo ($p['Stock'] <= 0) ? 'disabled' : ''; ?> id="cantidadProducto<?php echo $p['IdInventario']; ?>" class="cantidadProducto" onchange="cambiarCantidad(this)">
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    
                </div>
            </div>
            <div class="columna-derecha">
                <div class="resumen-box">
                    <h2>Resumen de Compra</h2>
                    <div class="resumen-linea">
                        <span class="resumen-label">Subtotal:</span>
                        <span class="resumen-valor">$ <span id="subtotal">0</span></span>
                    </div>

                    <div class="resumen-linea">
                        <span class="resumen-label">IVA (19%):</span>
                        <span class="resumen-valor"> $ <span id="iva">0</span></span>
                    </div>

                    <div class="resumen-linea last">
                        <span class="resumen-label">Total a pagar:</span>
                        <span class="resumen-valor">$<span id="valortotal">0</span> </span>
                    </div>

                    <button class="btn-procesar" name="registrar-venta" value="1">Registrar Venta</button>
                </div>
            </div>
    </form>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
    <script> 
        // new DataTable('#tabla-inventario');
         $('#tabla-venta').DataTable({
                language: {
                    lengthMenu: "Mostrar _MENU_",
                    zeroRecords: "No se encontraron resultados",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    infoEmpty: "No hay registros disponibles",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    search: "Buscar:",
                }
            });

        let subTotal = 0;
        const iva = 0.19;
        let total = 0;
        let productos= [];

        function generarSubtotal() {
            subTotal= 0;
            console.log("todos productos", productos);
            for (let producto of productos) {
                console.log("producto dentro de for", producto);
                if(producto){
                    subTotal += producto.precioProducto * producto.cantidad;
                }
            }

            $("#subtotal").text(subTotal);
            console.log("productos", productos)
            calcularTotales();
        }

        function calcularTotales(){
            totalIva = subTotal*iva;
            $("#iva").text(totalIva);
            valorTotal = subTotal+totalIva;
            $("#valortotal").text(valorTotal);

            $('#productosJson').val(JSON.stringify(productos));

        }

        function checkProductoChange(input) {
            const $checkbox = $(input); // Convertir a objeto jQuery
            const id = $checkbox.val();

            if ($checkbox.is(':checked')) {
                productos[id] = {
                    idProducto: id,
                    cantidad: $("#cantidadProducto" + id).val(),
                    precioProducto: +$("#precioProducto" + id).val()
                };
            } else {
                productos.splice(id, 1); // Elimina del arreglo
            }

            console.log("productos", productos);
            generarSubtotal();
        }

    
        function cambiarCantidad(input) {
            let $input = $(input);
            let idInput = $input.attr('id');
            let idProducto = idInput.replace(/\D/g, ''); // Extrae solo los números del ID

            if (productos[idProducto]) {
            productos[idProducto].cantidad = $input.val();
            }

            generarSubtotal();
        }

    </script>
</body>
</html>