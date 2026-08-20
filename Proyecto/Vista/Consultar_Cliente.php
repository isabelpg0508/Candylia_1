<?php
session_start();

if(!isset($_SESSION['usuarios'])){
    header("Location: Crud_Administrador_Inventario.html");
    exit();
}
// Verificar que existan datos
$datos = $_SESSION['usuarios'];
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
    <title>Consultar Clientes</title>
</head>
<body>
    <header>
        <h1>LISTA DE CLIENTES</h1>
        <a href="Cajero/Crud_Cajero_Cliente.html"><button class="regresar"><i class="fa-solid fa-arrow-left"></i></button></a>
    </header>
    
    <!-- Caja de búsqueda -->
    <div class="buscar-box">
        <div class="buscar-contenedor">
        <form method="GET" action="">
            <input type="text" name="buscar" placeholder="Buscar por nombre, precio..." 
                value="<?php echo isset($_GET['buscar']) ? $_GET['buscar'] : ''; ?>">
            <button type="submit"><i class="fa-solid fa-search"></i> Buscar</button>
        </form>
        <!-- Botón para mostrar todos los datos -->
        <form method="GET" action="">
            <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-rotate-left"></i> Limpiar búsqueda</button>
        </form>
        </div> 
       <div class="botones-filtro">
            <a href="../Controlador/LogicaCliente.php?consultar-cliente=2">
                <button type="button" class="btn btn-success">
                    <i class="fa-solid fa-receipt"></i> Ver Activos
                </button>
            </a>
        </div>
    </div>
    
    <div class="tabla-contenedor">
        <table class='table table-hover'>
            <thead>
                <tr>
                    <th>Documento Identidad</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Correo</th>
                    <th>Telefono</th>
                    <th>Genero</th>
                    <th>Fecha Nacimiento</th>
                    <th>Id Usuario Que Registra</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $busqueda = isset($_GET['buscar']) ? $_GET['buscar'] : '';

                    foreach($datos as $valor): 
                        if (($valor['Estado'] ?? 'A') !== 'A') {
                            continue; // Omitir los inactivos
                        }
                        // Si hay búsqueda, verificar coincidencia
                        if($busqueda != ''){
                            $encontrado = false;

                            if(stripos($valor['DocumentoIdentidad'], $busqueda) !== false ||
                            stripos($valor['Nombre'], $busqueda) !== false || //stripos busca si un texto está.
                           stripos($valor['Direccion'], $busqueda) !== false ||
                           stripos($valor['Correo'], $busqueda) !== false ||
                           stripos($valor['Telefono'], $busqueda) !== false){
                            $encontrado = true;
                        }
                            if(!$encontrado){
                                continue;
                            }
                        }
                    ?>
                    <tr>
                        <td><?php echo $valor['DocumentoIdentidad']; ?></td>
                        <td><?php echo $valor['Nombre']; ?></td>
                        <td><?php echo $valor['Direccion']; ?></td>
                        <td><?php echo $valor['Correo']; ?></td>
                        <td><?php echo $valor['Telefono']; ?></td>
                        <td><?php echo $valor['Genero']; ?></td>
                        <td><?php echo $valor['FechaNacimiento']; ?></td>
                        <td><?php echo $valor['IdUsuarioRegistra']; ?></td>
                        <td>
                            <!-- Solo botón Editar -->
                            <a href="../Controlador/LogicaCliente.php?actualizar-cliente=4&documento=<?php echo $valor['DocumentoIdentidad']; ?>">
                                <button class="btn-accion btn-editar">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>
                    </body>
                    </html>