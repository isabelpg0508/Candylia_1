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
    <title>REGISTRAR CLIENTE</title>
</head>
<body>
     <?php if($tipousuario=='Administrador'): ?>
        <a href="Crud_Administrador_Cliente.html"><button class="regresar"><i class="fa-solid fa-arrow-left"></i></button></a>
        <?php else:?>
        <a href='../Cajero/Crud_Cajero_Cliente.html'><button class='regresar'><i class='fa-solid fa-arrow-left'></i></button></a>
        <?php endif; ?>
    <section>
        <h1>Registrar Cliente</h1>
        <form method="POST" action="../../Controlador/LogicaCliente.php">
            <label for="documento">Documento Identidad:</label>
            <br>
            <input type="number" id="documento" name="DocumentoIdentidad" required>
            <br><br>
            <label for="nombre">Nombre:</label>
            <br>
            <input type="text" id="nombre" name="Nombre" required>
            <br><br>
            <label for="direccion">Direccion:</label>
            <br>
            <input type="text" id="direccion" name="Direccion">
            <br><br>
            <label for="correo">Correo:</label>
            <br>
            <input type="email" id="correo" name="Correo" required>
            <br><br>
            <label for="telefono">Telefono:</label>
            <br>
            <input type="tel" id="telefono" name="Telefono" required>
            <br><br>
            <label>Genero:</label>
            <div class="genero-grupo">
                <div class="genero">
                    <label for="masculino">Masculino</label>
                    <input type="radio" id="masculino" name="Genero" value="Masculino">
                </div>
                <div class="genero">
                    <label for="femenino">Femenino</label>
                    <input type="radio" id="femenino" name="Genero" value="Femenino">
                </div>
            </div>
            <br><br>
            <label for="fecha-nacimiento">Fecha de Nacimiento:</label>
            <br>
            <input type="date" id="fecha-nacimiento" name="FechaNacimiento" required>
            <br><br>
            <label for="IdUsuario">Id Usuario que Registra:</label>
            <br>
            <input type="number" id="IdUsuario" name="IdUsuario" value="<?php echo $_SESSION['IdUsuario']; ?>" readonly>
            <br><br>
            <button type="submit" name="registrar-cliente" value="1">Registrar</button>
        </form>
    </section>
</body>
</html>