<?php // Aparece el alert, si se recarga la página, el alert NO vuelve a aparecer
    session_start();

    if (isset($_SESSION['mensaje'])) {
        $mensaje = $_SESSION['mensaje'];
        echo "<script>alert('{$mensaje}');</script>";
        
        // Limpiar la sesión
        unset($_SESSION['mensaje']);
        unset($_SESSION['tipo_mensaje']);
    }
    $tipousuario = isset($_SESSION['TipoUsuario']) ? $_SESSION['TipoUsuario'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../Diseño_Formulario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <title>REGISTRAR INVENTARIO</title>
</head>
<body>
    <?php if($tipousuario=='Administrador'): ?>
        <a href="Crud_Administrador_Inventario.html"><button class="regresar"><i class="fa-solid fa-arrow-left"></i></button></a>
        <?php else:?>
        <a href='../OperarioBodega/Crud_OperarioBodega_Inventario.html'><button class='regresar'><i class='fa-solid fa-arrow-left'></i></button></a>
        <?php endif; ?>
    <section>
        <h1>Registrar Inventario</h1>
        <form class="inventario" method="POST" action="../../Controlador/LogicaInventario.php">
            <label for="nombre">Nombre:</label>
            <br>
            <input type="text" id="nombre" name="Nombre" required>
            <br><br>
            <label for="precio">Precio:</label>
            <br>
            <input type="number" id="precio" name="Precio" required>
            <br><br>
            <label for="stock">Stock:</label>
            <br>
            <input type="number" id="stock" name="Stock" required>
            <br><br>
            <label for="descripcion">Descripcion:</label>
            <br>
            <input type="text" id="descripcion" name="Descripcion">
            <br><br>
            <label for="IdUsuario">Id Usuario que Registra:</label>
            <br>
            <input type="number" id="IdUsuario" name="IdUsuario"  value="<?php echo isset($_SESSION['IdUsuario']) ? $_SESSION['IdUsuario'] : ''; ?>"  readonly>
            <br><br>
            <button type="submit" name="registrar-in" value="1">Registrar</button>
        </form>
    </section>
</body>
</html>