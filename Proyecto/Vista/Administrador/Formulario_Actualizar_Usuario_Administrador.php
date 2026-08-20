<?php
session_start();

// Verificar que existan los datos en sesión
if(!isset($_SESSION['usuario_editar']) || empty($_SESSION['usuario_editar'])) {
    echo "<script>
        alert('Error: No se encontraron datos del usuario');
        window.location.href='../../Controlador/LogicaUsuario.php?consultar-usu=2';
    </script>";
    exit();
}

$datos = $_SESSION['usuario_editar'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="../Diseño_Formulario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>ACTUALIZAR USUARIO</title>
</head>
<body>
    <a href="../../Controlador/LogicaUsuario.php?consultar-usu=2">
        <button class="regresar"><i class="fa-solid fa-arrow-left"></i></button>
    </a>
    <section>
        <h1>Actualizar Usuario</h1>
        <form method="POST" action="../../Controlador/LogicaUsuario.php" enctype="multipart/form-data">
            <input type="hidden" name="actualizar-usu" value="3">
            <input type="hidden" name="IdUsuario" value="<?php echo $datos['IdUsuario']; ?>">
            <input type="hidden" name="Certificacion_actual" value="<?php echo($datos['Certificacion']) ? $datos['Certificacion'] : ''; ?>">
            
            <label for="user">Nombre Usuario:</label>
            <br>
            <input type="text" value="<?php echo $datos['NombreUsuario']; ?>" id="user" name="NombreUsuario" required>
            <br><br>
            
            <label for="password">Contraseña:</label>
            <br>
            <input type="password" value="<?php echo $datos['Contrasena']; ?>" id="password" name="Contrasena" required>
            <br><br>
            
            <label for="tipo">Tipo de Usuario:</label>
            <br>
            <select id="tipo" disabled>
                <option value="Administrador" <?php if($datos['TipoUsuario'] == 'Administrador') echo 'selected'; ?>>Administrador</option>
                <option value="Cajero" <?php if($datos['TipoUsuario'] == 'Cajero') echo 'selected'; ?>>Cajero</option>
                <option value="OperarioBodega" <?php if($datos['TipoUsuario'] == 'OperarioBodega') echo 'selected'; ?>>Operario de Bodega</option>
            </select>
            <input type="hidden" name="TipoUsuario" value="<?php echo $datos['TipoUsuario']; ?>">
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
            
            <label for="fecha-nacimiento">Fecha de Nacimiento:</label>
            <br>
            <input type="date" value="<?php echo $datos['FechaNacimiento']; ?>" id="fecha-nacimiento" name="FechaNacimiento" required readonly>
            <br><br>
            
            <label for="titulo">Titulo Actual:</label>
            <br>
            <input type="text" value="<?php echo $datos['Titulo']; ?>" id="titulo" name="Titulo">
            <br><br>

            <label for="nivel">Nivel Educativo:</label>
            <br>
            <input type="text" value="<?php echo $datos['NivelEducativo']; ?>" id="nivel" name="NivelEducativo" required>
            <br><br>
            <label for="certificacion">Certificado(s):</label>
            <br>
            <input type="file" id="certificacion" name="Certificacion" value="<?php echo $datos['Certificacion']; ?>" >
            <br><br>
            
            <label for="habilidades">Habilidades Adicionales:</label>
            <br>
            <input type="text" value="<?php echo $datos['HabilidadesAdicionales']; ?>" id="habilidades" name="HabilidadesAdicionales">
            <br><br>
            
            <label for="empresa">Nombre de la Empresa Anterior:</label>
            <br>
            <input type="text" value="<?php echo $datos['NombreEmpresa']; ?>" id="empresa" name="NombreEmpresa" required>
            <br><br>
            
            <label for="cargo">Cargo que ocupaba:</label>
            <br>
            <input type="text" value="<?php echo $datos['CargoQueOcupaba']; ?>" id="cargo" name="CargoQueOcupaba" required>
            <br><br>
            
            <label for="duracion">Duración(meses) en la empresa anterior:</label>
            <br>
            <input type="number" value="<?php echo $datos['Duracion']; ?>" id="duracion" name="Duracion" required>
            <br><br>
            
            <label for="nombrejefe">Nombre Jefe Anterior:</label>
            <br>
            <input type="text" value="<?php echo $datos['NombreJefe']; ?>" id="nombrejefe" name="NombreJefe" required readonly>
            <br><br>
            
            <label for="telefonojefe">Telefono Jefe Anterior:</label>
            <br>
            <input type="number" value="<?php echo $datos['TelefonoJefe']; ?>" id="telefonojefe" name="TelefonoJefe" required readonly>
            <br><br>
            
            <button type="submit">Actualizar Usuario</button>
        </form>
    </section>
</body>
</html>