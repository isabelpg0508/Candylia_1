<?php
session_start();

// Verificar que existan datos
if(!isset($_SESSION['usuarios'])){
    header("Location: Crud_Administrador_Venta.html");
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
    <link rel="stylesheet" href="Diseño_Consultas.css">
    <title>Consultar Venta</title>
</head>
<body>
    <header>
        <h1>LISTA DE VENTA</h1>
        <a href="Cajero/Crud_Cajero_Venta.html"><button class="regresar"><i class="fa-solid fa-arrow-left"></i></button></a>
    </header>
    
    <!-- Caja de búsqueda -->
    <div class="buscar-box">
        <div class="buscar-contenedor">
        <form method="GET" action="../Controlador/LogicaVenta.php">
            <input type="hidden" name="consultar-venta" value="2">
            <input type="date" name="Fecha" required value="<?php echo isset($_GET['Fecha']) ? $_GET['Fecha'] : ''; ?>">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-search"></i> Buscar por fecha</button>
        </form>
        <!-- Botón para mostrar todos los datos -->
        <form method="GET" action="../Controlador/LogicaVenta.php">
            <input type="hidden" name="consultar-venta" value="2">
            <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-rotate-left"></i> Limpiar búsqueda</button>
        </form>
        </div> 
    </div>
    <div class="tabla-contenedor">
        <table class='table table-hover' id="tabla-venta">
            <thead>
                <tr>
                    <th>Id Venta</th>
                    <th>Fecha</th>
                    <th>Hora </th>
                    <th>Cantidad</th>
                    <th>SubTotal</th>
                    <th>IVA(19%)</th>
                    <th>Valor Total</th>
                    <th>Id Usuario Que Registra</th>
                    <th>Documento de Identidad del Cliente</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
                $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

                foreach($datos as $valor): 
                    // Filtrar por rango de fechas
                    if ($fecha_inicio != '' && $fecha_fin != '') {
                        if ($valor['Fecha'] < $fecha_inicio || $valor['Fecha'] > $fecha_fin) {
                            continue;
                        }
                    }
                ?>
                    <tr>
                        <td><?php echo $valor['IdVenta']; ?></td>
                        <td><?php echo $valor['Fecha']; ?></td>
                        <td><?php echo $valor['Hora']; ?></td>
                        <td><?php echo $valor['Cantidad']; ?></td>
                        <td><?php echo $valor['SubTotal']; ?></td>
                        <td><?php echo $valor['IVA']; ?></td>
                        <td><?php echo $valor['ValorTotal']; ?></td>
                        <td><?php echo $valor['IdUsuarioQueRegistra']; ?></td>
                        <td><?php echo $valor['DocumentoCliente']; ?></td>
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
        // new DataTable('#tabla-venta');
         $('#tabla-venta').DataTable({
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