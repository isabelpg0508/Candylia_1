<?php // Aparece el alert, si se recarga la página, el alert NO vuelve a aparecer
    session_start();

    if (isset($_SESSION['mensaje'])) {
        $mensaje = $_SESSION['mensaje'];
        echo "<script>alert('{$mensaje}');</script>";
        
        // Limpiar la sesión
        unset($_SESSION['mensaje']);
        unset($_SESSION['tipo_mensaje']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../Diseño_Formulario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <title>REGISTRAR USUARIO</title>
</head>
<body>
    <a href="Crud_Administrador_Usuario.html"><button class="regresar"><i class="fa-solid fa-arrow-left"></i></button></a>
    <section>
        <h1>Registrar Usuario</h1>
        <form method="POST" action="../../Controlador/LogicaUsuario.php" enctype="multipart/form-data">
            <label for="user">Nombre Usuario:</label>
            <br>
            <input type="text" id="user" name="NombreUsuario" required>
            <br><br>
            <label for="password">Contraseña:</label>
            <br>
            <input type="password" id="password" name="Contrasena" required>
            <br><br>
            <label for="tipo">Tipo de Usuario:</label>
            <br>
            <select id="tipo" name="TipoUsuario" required>
                <option value="" selected></option>
                <option value="Administrador">Administrador</option>
                <option value="Cajero">Cajero</option>
                <option value="OperarioBodega">Operario de Bodega</option>
            </select>
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
            <label for="fecha-nacimiento">Fecha de Nacimiento:</label>
            <br>
            <input type="date" id="fecha-nacimiento" name="FechaNacimiento" required>
            <br><br>
            <label for="titulo">Titulo Actual:</label>
            <br>
            <input type="text" id="titulo" name="Titulo">
            <br><br>
            <label for="nivel">Nivel Educativo:</label>
            <br>
            <input type="text" id="nivel" name="NivelEducativo" required>
            <br><br>
            <label for="certificacion">Certificado(s):</label>
            <br>
            <input type="file" id="certificacion" name="Certificacion" required>
            <br><br>
            <label for="habilidades">Habilidades Adicionales:</label>
            <br>
            <input type="text" id="habilidades" name="HabilidadesAdicionales">
            <br><br>
            <label for="empresa">Nombre de la Empresa Anterior:</label>
            <br>
            <input type="text" id="empresa" name="NombreEmpresa" required>
            <br><br>
            <label for="cargo">Cargo que ocupaba:</label>
            <br>
            <input type="text" id="cargo" name="CargoQueOcupaba" required>
            <br><br>
            <label for="duracion">Duración(meses) en la empresa anterior:</label>
            <br>
            <input type="number" id="duracion" name="Duracion" required>
            <br><br>
            <label for="nombrejefe">Nombre Jefe Anterior:</label>
            <br>
            <input type="text" id="nombrejefe" name="NombreJefe" required>
            <br><br>
            <label for="telefonojefe">Telefono Jefe Anterior:</label>
            <br>
            <input type="number" id="telefonojefe" name="TelefonoJefe" required>
            <br><br>
            <button type="submit" name="registrar-usu" value="1">Registrar</button>
        </form>
    </section>
</body>
</html>