<?php
session_start();
include "../Modelo/Inventario.php";
include "../Modelo/Venta.php";
$tipousuario = $_SESSION['TipoUsuario'];

    if(isset($_POST["registrar-venta"]) && $_POST["registrar-venta"] == "1"){

        $productosJson = isset($_POST['productosJson']) ? json_decode($_POST['productosJson'], true) : []; 

        $productosSeleccionados=[];
        $subtotal=0;

        //subtotal
        if (!empty($_POST['productosSeleccionados'])) {
            foreach($_POST['productosSeleccionados'] as $id){
            // se accede a la cantidad, primero posicion luego cantidad
            $cantidad = isset($productosJson[$id]['cantidad']) ? intval($productosJson[$id]['cantidad']) : 0;

            // precio crudo recibido (puede venir como "1200" o "1.200" o "1200,50")
            $rawPrecio = $productosJson[$id]['precioProducto'] ?? '0';

            // Convertir a número
            $precio = intval($rawPrecio); // intval si se guarda entero

            // si cantidad o precio inválidos, saltar
            if ($cantidad <= 0 || $precio <= 0) {
                continue;
                ;
            }
            $subtotal += $cantidad * $precio;

            $productosSeleccionados[] = [
                'IdInventario'    => $id,
                'PrecioProducto'  => $precio,
                'CantidadVendida' => $cantidad
            ];
            }
        }

        //Iva=19%
        $iva= round($subtotal*0.19, 2);
        $total= round($subtotal+$iva,2);

        $datosVenta=[
            'Cantidad'=> count($productosSeleccionados),
            'SubTotal'=> $subtotal,
            'IVA'=>$iva,
            'ValorTotal'=> $total,
            'IdUsuario'=> $_POST['IdUsuario'],
            'DocumentoIdentidad'=>$_POST['DocumentoIdentidad']
        ];

        $venta= new venta();
        $resultado = $venta->Registrar($datosVenta,$productosSeleccionados);
        if($resultado instanceof Exception){
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        }
        else if($resultado===true){
            echo "<script>
                    alert ('Venta creada con éxito');
                    window.location.href='../Vista/Administrador/Registrar_Venta_Administrador.php';        
            </script>";
        }
        else{
            echo "<script>
                    alert ('Error al registrar la venta');
                    window.location.href='../Vista/Administrador/Registrar_Venta_Administrador.php';        
            </script>";
        }

    }

    else if(isset($_GET["consultar-venta"]) && $_GET["consultar-venta"] == "2"){
        //Consultar
        $consulta = new venta();
        if (!empty($_GET['Fecha'])) {
            $datos = $consulta->ConsultarPorFecha($_GET['Fecha']);
        } 
        else {
            $datos = $consulta->Consultar();
        }

        if($datos instanceof Exception){
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        }
        else{
            $_SESSION['usuarios'] = $datos;
            if($tipousuario=='Administrador'){
                header("Location: ../Vista/Administrador/Consultar_Venta_Administrador.php");
                    exit();
            }
            else{
                header("Location: ../Vista/Consultar_Venta.php");
                exit();
            }
        }
    }

    else if (isset($_GET["inactivos"]) && $_GET["inactivos"] == "2") {

        $consulta = new venta();
        $devuelto = $consulta->ConsultarInactivos();

        if ($devuelto instanceof Exception) {
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        } 
        else if ($_SESSION['TipoUsuario'] != 'Administrador') {
            echo "<script>alert('Acceso denegado'); 
            window.location.href='../Vista/InicioSesion/Login.html';</script>";
            exit();
        }
        else {
            $_SESSION['usuarios'] = $devuelto;
            header("Location: ../Vista/Administrador/Consultar_Venta_Administrador.php?inactivos=2");
            exit();
        }
    }

    else if (isset($_GET["restaurar"]) && $_GET["restaurar"] == "2" && isset($_GET["id"])) {

        $idventa = $_GET["id"];
        $venta = new venta();
        $resultado = $venta->RestaurarVenta($idventa);
        if ($resultado instanceof Exception) {
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        } 
        else if ($resultado === true) {
            echo "<script>
                    alert('Venta restaurada correctamente');
                    window.location.href='../Vista/Administrador/Consultar_Venta_Administrador.php?inactivos=2';
                </script>";
        } else {
            echo "<script>
                    alert('Error al restaurar la venta');
                    window.location.href='../Vista/Administrador/Consultar_Venta_Administrador.php?inactivos=2';
                </script>";
        }
    }
    
    else if (isset($_GET["eliminar-venta"]) && $_GET["eliminar-venta"] == "4"){
        //Eliminar
        
        $eliminar= new venta();
        $devuelto = $eliminar->Eliminar($_GET["id"]);

        if($devuelto instanceof Exception){
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        }
         else if ($_SESSION['TipoUsuario'] != 'Administrador') {
            echo "<script>alert('Acceso denegado'); 
            window.location.href='../Vista/InicioSesion/Login.html';</script>";
        exit();
        }
        else{
            echo "<script>alert('Eliminacion exitosa');
                window.location.href = '../Vista/Administrador/Consultar_Venta_Administrador.php';
            </script>";
        }
    }
    
    else if (isset($_GET["reporte"]) && $_GET["reporte"] == "1") {
        
        $reporte = new venta();
        $usuarios = $reporte->Consultar();
            // Cabeceras para forzar la descarga del archivo Excel
            header("Content-Type: application/vnd.ms-excel; charset=utf-8");
            header("Content-Disposition: attachment; filename=reporte_venta.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            // Encabezados de columnas
            echo "Id Venta\tFecha\tHora\tCantidad\tSubTotal\tIVA\tValor Total\tId Usuario Que Registra\tDocumento de Identidad del Cliente\n";

            // Datos
            foreach ($usuarios as $i) {
                echo "{$i['IdVenta']}\t{$i['Fecha']}\t{$i['Hora']}\t{$i['Cantidad']}\t{$i['SubTotal']}\t{$i['IVA']}\t{$i['ValorTotal']}\t{$i['IdUsuarioQueRegistra']}\t{$i['DocumentoCliente']}\n";
            }

            exit; // Termina la ejecución aquí
    }
?>