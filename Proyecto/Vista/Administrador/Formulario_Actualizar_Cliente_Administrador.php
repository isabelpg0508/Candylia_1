<?php
session_start();

// Verificar que existan los datos en sesión
if(!isset($_SESSION['cliente_editar']) || empty($_SESSION['cliente_editar'])) {
    echo "<script>
        alert('Error: No se encontraron datos del usuario');
        window.location.href='../../Controlador/LogicaCliente.php?consultar-cliente=2';
    </script>";
    exit();
}

$datos = $_SESSION['cliente_editar'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="../Diseño_Formulario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>ACTUALIZAR CLIENTE</title>
</head>
<body>
    <a href="../../Controlador/LogicaCliente.php?consultar-cliente=2">
        <button class="regresar"><i class="fa-solid fa-arrow-left"></i></button>
    </a>
    <section>
        <h1>Actualizar Cliente
        </h1>
        
        <form method="POST" action="../../Controlador/LogicaCliente.php">
            <input type="hidden" name="actualizar-cliente" value="3">
            
            <label for="documento">Documento Identidad:</label>
            <br>
            <input type="number" value="<?php echo $datos['DocumentoIdentidad']; ?>" id="documento" name="DocumentoIdentidad" required readonly>
            <br><br>
            <label for="nombre">Nombre:</label>
            <br>
            <input type="text" value="<?php echo $datos['Nombre']; ?>" id="nombre" name="Nombre" required>
            <br><br>
            <label for="direccion">Direccion:</label>
            <br>
            <input type="text" value="<?php echo $datos['Direccion']; ?>" id="direccion" name="Direccion">
            <br><br>
            <label for="correo">Correo:</label>
            <br>
            <input type="email" value="<?php echo $datos['Correo']; ?>" id="correo" name="Correo" required readonly>
            <br><br>
            <label for="telefono">Telefono:</label>
            <br>
            <input type="tel" value="<?php echo $datos['Telefono']; ?>" id="telefono" name="Telefono" required>
            <br><br>
            <label>Genero:</label>
            <div class="genero-grupo">
                <div class="genero">
                    <label for="masculino">Masculino</label>
                    <input type="radio" <?php if($datos['Genero'] == 'Masculino') echo 'checked'; ?> id="masculino" name="Genero" value="Masculino">
                </div>
                <div class="genero">
                    <label for="femenino">Femenino</label>
                    <input type="radio" <?php if($datos['Genero'] == 'Femenino') echo 'checked'; ?> id="femenino" name="Genero" value="Femenino">
                </div>
            </div>
            <br><br>
            <label for="fecha-nacimiento">Fecha de Nacimiento:</label>
            <br>
            <input type="date" value="<?php echo $datos['FechaNacimiento']; ?>" id="fecha-nacimiento" name="FechaNacimiento" required readonly>
            <br><br>
            <label for="IdUsuario">Id Usuario que Registra:</label>
            <br>
            <input type="number" id="IdUsuario" name="IdUsuario" value="<?php echo $_SESSION['IdUsuario']; ?>" readonly>
            <br><br>
            <button type="submit">Actualizar Cliente</button>
        </form>
    </section>
</body>
</html>