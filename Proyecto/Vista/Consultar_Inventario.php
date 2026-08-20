<?php
session_start();

// Verificar que existan datos
if(!isset($_SESSION['usuarios'])){
    header("Location: Crud_Administrador_Inventario.html");
    exit();
}

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
    <title>Consultar Inventario</title>
</head>
<body>
    <header>
        <h1>LISTA DE INVENTARIO</h1>
        <?php if($tipousuario=='Cajero'): ?>
        <a href="Cajero/Crud_Cajero_Inventario.html"><button class="regresar"><i class="fa-solid fa-arrow-left"></i></button></a>
        <?php else:?>
        <a href='OperarioBodega/Crud_OperarioBodega_Inventario.html'><button class='regresar'><i class='fa-solid fa-arrow-left'></i></button></a>
        <?php endif; ?>
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
            <a href="../Controlador/LogicaInventario.php?consultar-in=2">
                <button type="button" class="btn btn-success">
                    <i class="fa-solid fa-receipt"></i> Ver Activos
                </button>
            </a>

            <?php if ($tipousuario != 'Cajero'): ?>
                <!-- Solo usuarios distintos a Cajero pueden crear Excel -->
                <a href="../Controlador/LogicaInventario.php?reporte=1">
                    <button type="button" class="btn btn-success">
                        <i class="fa-solid fa-file-excel"></i> Crear Excel
                    </button>
                </a>
            <?php else: ?>
                <!-- Botón deshabilitado para cajeros -->
                <button type="button" class="btn btn-secondary" disabled title="No tienes permisos para exportar Excel">
                    <i class="fa-solid fa-file-excel"></i> Crear Excel
                </button>
            <?php endif; ?>
        </div>

    </div>
    
    <div class="tabla-contenedor">
        <table class='table table-hover'>
            <thead>
                <tr>
                    <th>Id Inventario</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Descripcion</th>
                    <th>Id Usuario Que Registra</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $busqueda = isset($_GET['buscar']) ? $_GET['buscar'] : '';

                    foreach($datos as $valor): 
                        // Mostrar solo productos activos
                        if (($valor['Estado'] ?? 'A') !== 'A') {
                            continue; // Omitir los inactivos
                        }
                        // Si hay búsqueda, verificar coincidencia
                        if($busqueda != ''){
                            $encontrado = false;
                            if(stripos($valor['Nombre'], $busqueda) !== false ||
                            stripos($valor['Precio'], $busqueda) !== false ||
                            stripos($valor['Descripcion'], $busqueda) !== false){
                                $encontrado = true;
                            }
                            if(!$encontrado){
                                continue;
                            }
                        }
                    ?>
                    <tr>
                        <td><?php echo $valor['IdInventario']; ?></td>
                        <td><?php echo $valor['Nombre']; ?></td>
                        <td><?php echo $valor['Precio']; ?></td>
                        <td><?php echo $valor['Stock']; ?></td>
                        <td><?php echo $valor['Descripcion']; ?></td>
                        <td><?php echo $valor['IdUsuarioRegistra']; ?></td>
                        <td>
                            <!-- Solo botón Editar -->
                            <a href="../Controlador/LogicaInventario.php?actualizar-in=4&id=<?php echo $valor['IdInventario']; ?>">
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