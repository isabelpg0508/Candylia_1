<?php
session_start();

$usuario = $_SESSION['mi_usuario_fulldata'];
$tipousuario = isset($_SESSION['TipoUsuario']) ? $_SESSION['TipoUsuario'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="Diseño_Consultas.css">
    <title>Consultar Usuarios</title>
</head>
<body>
    <header>
        <h1>LISTA DE USUARIOS</h1>
        <?php if($tipousuario=='Cajero'): ?>
        <a href="Cajero/Crud_Cajero_Usuario.html"><button class="regresar"><i class="fa-solid fa-arrow-left"></i></button></a>
        <?php elseif($tipousuario=='OperarioBodega'):?>
        <a href='OperarioBodega/Crud_OperarioBodega_Usuario.html'><button class='regresar'><i class='fa-solid fa-arrow-left'></i></button></a>
        <?php endif; ?>
    </header>
    
    <div class="tabla-contenedor">
        <table class='table table-hover'>
            <thead>
                <tr>
                    <th>Id Usuario</th>
                    <th>Nombre Usuario</th>
                    <th>Contraseña</th>
                    <th>Tipo Usuario</th>
                    <th>Nombre</th>
                    <th>Direccion</th>
                    <th>Correo</th>
                    <th>Fecha Nacimiento</th>
                    <th>Titulo</th>
                    <th>Nivel Educativo</th>
                    <th>Certificacion</th>
                    <th>Habilidades Adicionales</th>
                    <th>Nombre Empresa</th>
                    <th>Cargo Que Ocupaba</th>
                    <th>Duracion</th>
                    <th>Nombre Jefe</th>
                    <th>Telefono Jefe</th>
                    <th>Acción</th>
                </tr>
            </thead>
          <tbody>
            <tr>
                <td><?php echo $usuario['IdUsuario']; ?></td>
                <td><?php echo $usuario['NombreUsuario']; ?></td>
                <td><?php echo $usuario['Contrasena']; ?></td>
                <td><?php echo $usuario['TipoUsuario']; ?></td>
                <td><?php echo $usuario['Nombre']; ?></td>
                <td><?php echo $usuario['Direccion']; ?></td>
                <td><?php echo $usuario['Correo']; ?></td>
                <td><?php echo $usuario['FechaNacimiento']; ?></td>
                <td><?php echo $usuario['Titulo']; ?></td>
                <td><?php echo $usuario['NivelEducativo']; ?></td>
                <td><?php echo $usuario['Certificacion']; ?></td>
                <td><?php echo $usuario['HabilidadesAdicionales']; ?></td>
                <td><?php echo $usuario['NombreEmpresa']; ?></td>
                <td><?php echo $usuario['CargoQueOcupaba']; ?></td>
                <td><?php echo $usuario['Duracion']; ?></td>
                <td><?php echo $usuario['NombreJefe']; ?></td>
                <td><?php echo $usuario['TelefonoJefe']; ?></td>
                <td>
                    <form method="POST" action="../Controlador/LogicaUsuario.php?actualizar-mi-usuario=4&id=<?php echo $usuario['IdUsuario']; ?>">
                        <input type="hidden" name="IdUsuario" value="<?php echo $usuario['IdUsuario']; ?>">
                        <button type="submit" class="btn btn-info">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </form>
                </td>
            </tr>
        </tbody>
        </table>
    </div>
</body>
</html>
