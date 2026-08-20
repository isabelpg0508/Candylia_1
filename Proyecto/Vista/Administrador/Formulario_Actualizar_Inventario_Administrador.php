<?php
session_start();

// Verificar que existan los datos en sesión
if(!isset($_SESSION['inventario_editar']) || empty($_SESSION['inventario_editar'])) {
    echo "<script>
        alert('Error: No se encontraron datos del inventario');
        window.location.href='../../Controlador/LogicaInventario.php?consultar-in=2';
    </script>";
    exit();
}
$datos = $_SESSION['inventario_editar'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="../Diseño_Formulario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>ACTUALIZAR INVENTARIO</title>
</head>
<body>
    <a href="../../Controlador/LogicaInventario.php?consultar-in=2">
        <button class="regresar"><i class="fa-solid fa-arrow-left"></i></button>
    </a>
    <section>
        <h1>Actualizar Inventario</h1>
        <form method="POST" action="../../Controlador/LogicaInventario.php">
            <input type="hidden" name="actualizar-in" value="3">
            <input type="hidden" name="IdInventario" value="<?php echo $datos['IdInventario']; ?>">
            
            <label for="nombre">Nombre:</label>
            <br>
            <input type="text" value="<?php echo $datos['Nombre']; ?>" id="nombre" name="Nombre" required readonly>
            <br><br>
            <label for="precio">Precio:</label>
            <br>
            <input type="number" value="<?php echo $datos['Precio']; ?>" id="precio" name="Precio" required>
            <br><br>
            <label for="stock">Stock:</label>
            <br>
            <input type="number" value="<?php echo $datos['Stock']; ?>" id="stock" name="Stock" required>
            <br><br>
            <label for="descripcion">Descripcion:</label>
            <br>
            <input type="text" value="<?php echo $datos['Descripcion']; ?>" id="descripcion" name="Descripcion">
            <br><br>
            <label for="IdUsuario">Id Usuario que Registra:</label>
            <br>
            <input type="number" id="IdUsuario" name="IdUsuario"  value="<?php echo $_SESSION['IdUsuario']; ?>" readonly>
            <br><br>
            <button type="submit">Actualizar Producto</button>
        </form>
    </section>
</body>
</html>