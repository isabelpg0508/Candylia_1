<?php
session_start();
include "../Modelo/Inventario.php";
$tipousuario = $_SESSION['TipoUsuario'];

    //isset:verifica si una variable está definida y no es null
     if(isset($_POST["registrar-in"]) && $_POST["registrar-in"] == "1"){
        //Registrar
         if ($tipousuario == 'Administrador' || $tipousuario == 'OperarioBodega'){
            $idusuario = isset($_POST['IdUsuario']) ? $_POST['IdUsuario'] : (isset($_SESSION['IdUsuario']) ? $_SESSION['IdUsuario'] : null);
            $registro=new inventario;
            $devuelto=$registro->Registrar($_POST["Nombre"],$_POST["Precio"],$_POST["Stock"],$_POST["Descripcion"],$idusuario);

                if($devuelto instanceof Exception){
                    echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
                }
                else if($devuelto === "duplicado"){
                    $_SESSION['mensaje'] = 'Producto existente, reintentelo';
                    $_SESSION['tipo_mensaje'] = 'duplicado';
                    header("Location: ../Vista/Administrador/Registrar_Inventario_Administrador.php");
                    exit();
                }
                else if($devuelto){
                    $_SESSION['mensaje'] = 'Producto creado de manera exitosa';
                    $_SESSION['tipo_mensaje'] = 'success';
                    header("Location: ../Vista/Administrador/Registrar_Inventario_Administrador.php");
                    exit();
                }
                else{
                    $_SESSION['mensaje'] = 'Los datos no corresponden, intente de nuevo';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header("Location: ../Vista/Administrador/Registrar_Inventario_Administrador.php");
                    exit();
                }
        }
            
    }

    else if(isset($_GET["consultar-in"]) && $_GET["consultar-in"] == "2"){
        //Consultar
        $consulta = new inventario();
        $datos = $consulta->Consultar();

        if($datos instanceof Exception){
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        }
        else{
            $_SESSION['usuarios'] = $datos;
            if($tipousuario=='Administrador'){
                header("Location: ../Vista/Administrador/Consultar_Inventario_Administrador.php");
                    exit();
            }
            else{
                header("Location: ../Vista/Consultar_Inventario.php");
                exit();
            }
        }   
    }

    else if (isset($_GET["inactivos"]) && $_GET["inactivos"] == "2") {

        $consulta = new inventario();
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
            header("Location: ../Vista/Administrador/Consultar_Inventario_Administrador.php?inactivos=2");
            exit();
        }
    }

    else if (isset($_GET["restaurar"]) && $_GET["restaurar"] == "2" && isset($_GET["id"])) {

        $idinventario = $_GET["id"];
        $inventario = new inventario();
        $resultado = $inventario->RestaurarInventario($idinventario);
        if ($resultado instanceof Exception) {
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        } 
        else if ($resultado === true) {
            echo "<script>
                    alert('Producto restaurado correctamente');
                    window.location.href='../Vista/Administrador/Consultar_Inventario_Administrador.php?inactivos=2';
                </script>";
        } else {
            echo "<script>
                    alert('Error al restaurar el producto');
                    window.location.href='../Vista/Administrador/Consultar_Inventario_Administrador.php?inactivos=2';
                </script>";
        }
    }

    else if (isset($_POST["actualizar-in"]) && $_POST["actualizar-in"] == "3") {
        
        $actualizar = new inventario();

        $idinventario = $_POST["IdInventario"];
        $nombre = $_POST["Nombre"];
        $precio = $_POST["Precio"];
        $stock = $_POST["Stock"];
        $descripcion = $_POST["Descripcion"];
        $idusuario = $_POST["IdUsuario"];
        $resultado = $actualizar->Actualizar($nombre,$precio,$stock,$descripcion,$idusuario,$idinventario);

        if ($resultado instanceof Exception) {
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";;
        } 
        else if ($resultado == true) {
            echo "<script>
                alert('Producto actualizado exitosamente');
                window.location.href='LogicaInventario.php?consultar-in=2';
            </script>";
        } 
        else {
            echo "<script>
                alert('Error al actualizar');
                window.location.href='LogicaInventario.php?consultar-in=2';
            </script>";
        }
    }

    else if (isset($_GET["actualizar-in"]) && $_GET["actualizar-in"] == "4" && isset($_GET["id"])) {
    //Actualizar

        $actualizar = new inventario();
        $usuarios = $actualizar->Consultar();
        
        if($usuarios instanceof Exception){
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        }
        else{
            $datos = null;
            foreach($usuarios as $usuario) {
                if($usuario['IdInventario'] == $_GET["id"]) {
                    $datos = $usuario;
                    break;
                }
            }
            
            if($datos) {
                // Guardar en sesión y redirigir
                $_SESSION['inventario_editar'] = $datos;
                header("Location: ../Vista/Administrador/Formulario_Actualizar_Inventario_Administrador.php");
                exit();
            } 
            else {
                echo "<script>
                    alert('Producto no encontrado'); 
                    window.location.href='LogicaInventario.php?consultar-in=2';
                </script>";
            }
        }
    }

    else if (isset($_GET["eliminar-in"]) && $_GET["eliminar-in"] == "4"){
        //Eliminar
        
        $eliminar= new inventario();
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
                window.location.href = '../Vista/Administrador/Consultar_Inventario_Administrador.php';
            </script>";
        }
    }
    
    else if (isset($_GET["reporte"]) && $_GET["reporte"] == "1") {
        
        $reporte = new inventario();
        $usuarios = $reporte->Consultar();
            // Cabeceras para forzar la descarga del archivo Excel
            header("Content-Type: application/vnd.ms-excel; charset=utf-8");
            header("Content-Disposition: attachment; filename=reporte_inventario.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            // Encabezados de columnas
            echo "Id Inventario\tNombre\tPrecio\tStock\tDescripcion\tId Usuario Que Registra\n";

            // Datos
            foreach ($usuarios as $i) {
                echo "{$i['IdInventario']}\t{$i['Nombre']}\t{$i['Precio']}\t{$i['Stock']}\t{$i['Descripcion']}\t{$i['IdUsuarioRegistra']}\n";
            }

            exit; // Termina la ejecución aquí
    }
?>