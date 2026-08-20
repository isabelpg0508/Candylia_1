<?php
session_start();

// Verificar que existan datos
if(!isset($_SESSION['usuarios'])){
    header("Location: Crud_Administrador_Usuario.html");
    exit();
}

$datos = $_SESSION['usuarios'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../Diseño_Consultas.css">
    <title>Consultar Usuarios</title>
</head>
<body>
    <header>
        <h1>LISTA DE USUARIOS</h1>
        <a href="Crud_Administrador_Usuario.html"><button class="regresar"><i class="fa-solid fa-arrow-left"></i></button></a>
    </header>
    
    <!-- Caja de búsqueda -->
    <div class="buscar-box">
        <div class="buscar-contenedor">
        <form method="GET" action="">
            <input type="text" name="buscar" placeholder="Buscar por nombre, correo, empresa..." 
                value="<?php echo isset($_GET['buscar']) ? $_GET['buscar'] : ''; ?>">
            <button type="submit"><i class="fa-solid fa-search"></i> Buscar</button>
        </form>
        <!-- Botón para mostrar todos los datos -->
        <form method="GET" action="">
            <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-rotate-left"></i> Limpiar búsqueda</button>
        </form>
        </div>
        <div class="botones-filtro">
            <a href="../../Controlador/LogicaUsuario.php?consultar-usu=2"><button type="button" class="btn btn-success"><i class="fa-solid fa-user-check"></i> Ver Activos</button></a>
            <a href="../../Controlador/LogicaUsuario.php?inactivos=2"><button type="button" class="btn btn-secondary"><i class="fa-solid fa-user-slash"></i> Ver Inactivos</button></a>
        </div>
    </div>
    
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
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Si hay búsqueda, filtrar los datos
                $busqueda = isset($_GET['buscar']) ? $_GET['buscar'] : '';
                
                foreach($datos as $valor): 
                    // Si hay búsqueda, verificar si el registro coincide
                    if($busqueda != ''){
                        $encontrado = false;
                        // Buscar en varios campos
                        if(stripos($valor['Nombre'], $busqueda) !== false ||
                           stripos($valor['NombreUsuario'], $busqueda) !== false || //stripos busca si un texto está.
                           stripos($valor['Correo'], $busqueda) !== false ||
                           stripos($valor['NombreEmpresa'], $busqueda) !== false ||
                           stripos($valor['CargoQueOcupaba'], $busqueda) !== false){
                            $encontrado = true;
                        }
                        
                        // Si no se encontró, saltar este registro
                        if(!$encontrado){
                            continue;
                        }
                    }
                ?>
                    <tr>
                        <td><?php echo $valor['IdUsuario']; ?></td>
                        <td><?php echo $valor['NombreUsuario']; ?></td>
                        <td><?php echo $valor['Contrasena']; ?></td>
                        <td><?php echo $valor['TipoUsuario']; ?></td>
                        <td><?php echo $valor['Nombre']; ?></td>
                        <td><?php echo $valor['Direccion']; ?></td>
                        <td><?php echo $valor['Correo']; ?></td>
                        <td><?php echo $valor['FechaNacimiento']; ?></td>
                        <td><?php echo $valor['Titulo'] ?? 'N/A'; ?></td>
                        <td><?php echo $valor['NivelEducativo'] ?? 'N/A'; ?></td>
                        <td><?php echo $valor['Certificacion'] ?? 'N/A'; ?></td>
                        <td><?php echo $valor['HabilidadesAdicionales'] ?? 'N/A'; ?></td>
                        <td><?php echo $valor['NombreEmpresa']; ?></td>
                        <td><?php echo $valor['CargoQueOcupaba']; ?></td>
                        <td><?php echo $valor['Duracion']; ?></td>
                        <td><?php echo $valor['NombreJefe']; ?></td>
                        <td><?php echo $valor['TelefonoJefe']; ?></td>
                        <td><?php if (($valor['Estado'] ?? '') == 'I'): // Evita una advertencia incluso si la consulta falla al traerlo?>
                        <!-- Botón Restaurar -->
                        <a href="../../Controlador/LogicaUsuario.php?restaurar=2&id=<?php echo $valor['IdUsuario']; ?>" 
                        onclick="return confirm('¿Desea restaurar este usuario?')">
                            <button class="btn-accion btn-eliminar">
                                <i class="fa-solid fa-rotate-left"></i>
                            </button>
                        </a>
                        <!-- Botón Editar deshabilitado -->
                        <button class="btn-accion btn-editar" disabled title="No disponible para usuarios inactivos">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <?php else: ?>
                        <!-- Botón Editar habilitado -->
                        <a href="../../Controlador/LogicaUsuario.php?actualizar-usu=4&id=<?php echo $valor['IdUsuario']; ?>">
                            <button class="btn-accion btn-editar">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </a>
                        <!-- Botón Eliminar -->
                        <a href="../../Controlador/LogicaUsuario.php?eliminar-usu=4&id=<?php echo $valor['IdUsuario']; ?>" 
                        onclick="return confirm('¿Seguro que desea eliminar este usuario?')">
                            <button class="btn-accion btn-eliminar">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </a>
                        <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>