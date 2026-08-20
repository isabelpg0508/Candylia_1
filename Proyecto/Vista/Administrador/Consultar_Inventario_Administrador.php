<?php
session_start();

// Verificar que existan datos
if(!isset($_SESSION['usuarios'])){
    header("Location: Crud_Administrador_Inventario.html");
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
    <link src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet"/>
    <link src="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../Diseño_Consultas.css">
    <title>Consultar Inventario</title>
</head>
<body>
    <header>
        <h1>LISTA DE INVENTARIO</h1>
        <a href="Crud_Administrador_Inventario.html"><button class="regresar"><i class="fa-solid fa-arrow-left"></i></button></a>
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
            <a href="../../Controlador/LogicaInventario.php?consultar-in=2"><button type="button" class="btn btn-success"><i class="fa-solid fa-receipt"></i> Ver Activos</button></a>
            <a href="../../Controlador/LogicaInventario.php?inactivos=2"><button type="button" class="btn btn-secondary"><i class="fa-solid fa-ban"></i> Ver Inactivos</button></a>
            <a href="../../Controlador/LogicaInventario.php?reporte=1"><button type="button" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Crear Excel</button></a>
        </div>
    </div>
    
    <div class="tabla-contenedor">
        <table class='table table-hover' id="tabla-inventario">
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
                // Si hay búsqueda, filtrar los datos
                $busqueda = isset($_GET['buscar']) ? $_GET['buscar'] : '';
                
                foreach($datos as $valor): 
                    // Si hay búsqueda, verificar si el registro coincide
                    if($busqueda != ''){
                        $encontrado = false;
                        // Buscar en varios campos
                        if(stripos($valor['Nombre'], $busqueda) !== false ||
                           stripos($valor['Precio'], $busqueda) !== false || //stripos busca si un texto está.
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
                        <td><?php if (($valor['Estado'] ?? '') == 'I'): // Evita una advertencia incluso si la consulta falla al traerlo?>
                        <!-- Botón Restaurar -->
                        <a href="../../Controlador/LogicaInventario.php?restaurar=2&id=<?php echo $valor['IdInventario']; ?>" 
                        onclick="return confirm('¿Desea restaurar este producto?')">
                            <button class="btn-accion btn-eliminar">
                                <i class="fa-solid fa-rotate-left"></i>
                            </button>
                        </a>
                        <!-- Botón Editar deshabilitado -->
                        <button class="btn-accion btn-editar" disabled title="No disponible para productos inactivos">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <?php else: ?>
                        <!-- Botón Editar habilitado -->
                        <a href="../../Controlador/LogicaInventario.php?actualizar-in=4&id=<?php echo $valor['IdInventario']; ?>">
                            <button class="btn-accion btn-editar">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </a>
                        <!-- Botón Eliminar -->
                        <a href="../../Controlador/LogicaInventario.php?eliminar-in=4&id=<?php echo $valor['IdInventario']; ?>" 
                        onclick="return confirm('¿Seguro que desea eliminar este producto?')">
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
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>

    <script> 
        // new DataTable('#tabla-inventario');
         $('#tabla-inventario').DataTable({
                language: {
                    lengthMenu: "Mostrar _MENU_",
                    zeroRecords: "No se encontraron resultados",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    infoEmpty: "No hay registros disponibles",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    search: "Buscar:",
                }
            });

    </script>
</body>
</html>