<?php
session_start();
include "../Modelo/Cliente.php";
$tipousuario = $_SESSION['TipoUsuario'];
    
    //isset:verifica si una variable está definida y no es null
    if(isset($_POST["registrar-cliente"]) && $_POST["registrar-cliente"] == "1"){
        //Registrar
        if ($tipousuario == 'Administrador' || $tipousuario == 'Cajero'){
            $idUsuario = isset($_POST['IdUsuario']) ? $_POST['IdUsuario'] : (isset($_SESSION['IdUsuario']) ? $_SESSION['IdUsuario'] : null);
            $registro=new cliente;
            $devuelto=$registro->Registrar($_POST["DocumentoIdentidad"],$_POST["Nombre"],$_POST["Direccion"],$_POST["Correo"],$_POST["Telefono"],$_POST["Genero"],$_POST["FechaNacimiento"],$_POST["IdUsuario"]);
            if($devuelto instanceof Exception){
                echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
            }
            else if($devuelto === "duplicado"){
                $_SESSION['mensaje'] = 'Cliente existente, reintentelo';
                $_SESSION['tipo_mensaje'] = 'duplicado';
                header("Location: ../Vista/Administrador/Registrar_Cliente_Administrador.php");
                exit();
            }
            else if($devuelto){
                $_SESSION['mensaje'] = 'Cliente creado de manera exitosa';
                $_SESSION['tipo_mensaje'] = 'success';
                header("Location: ../Vista/Administrador/Registrar_Cliente_Administrador.php");
                exit();
            }
            else{
                $_SESSION['mensaje'] = 'Los datos no corresponden, intente de nuevo';
                $_SESSION['tipo_mensaje'] = 'error';
                header("Location: ../Vista/Administrador/Registrar_Cliente_Administrador.php");
                exit();
            }
        }
    }

    else if(isset($_GET["consultar-cliente"]) && $_GET["consultar-cliente"] == "2"){
    //Consultar
    
        $consulta = new cliente();
        $datos = $consulta->Consultar();

        if($datos instanceof Exception){
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        }
        else{
            $_SESSION['usuarios'] = $datos;
            if($tipousuario=='Administrador'){
                header("Location: ../Vista/Administrador/Consultar_Cliente_Administrador.php");
                    exit();
            }
            else{
                header("Location: ../Vista/Consultar_Cliente.php");
                exit();
            }
        }
    }

    else if (isset($_GET["inactivos"]) && $_GET["inactivos"] == "2") {

        $consulta = new cliente();
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
            header("Location: ../Vista/Administrador/Consultar_Cliente_Administrador.php?inactivos=2");
            exit();
        }
    }

    else if (isset($_GET["restaurar"]) && $_GET["restaurar"] == "2" && isset($_GET["documento"])) {

        $documento = $_GET["documento"];
        $cliente = new cliente();
        $resultado = $cliente->RestaurarCliente($documento);
        if ($resultado instanceof Exception) {
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        } 
        else if ($resultado === true) {
            echo "<script>
                    alert('Cliente restaurado correctamente');
                    window.location.href='../Vista/Administrador/Consultar_Cliente_Administrador.php?inactivos=2';
                </script>";
        } else {
            echo "<script>
                    alert('Error al restaurar el cliente');
                    window.location.href='../Vista/Administrador/Consultar_Cliente_Administrador.php?inactivos=2';
                </script>";
        }
    }

    else if (isset($_POST["actualizar-cliente"]) && $_POST["actualizar-cliente"] == "3") {
        
        $actualizar = new cliente();

        $documento = $_POST["DocumentoIdentidad"];
        $nombre = $_POST["Nombre"];
        $direccion = $_POST["Direccion"];
        $correo = $_POST["Correo"];
        $telefono = $_POST["Telefono"];
        $genero = $_POST["Genero"];
        $nacimiento = $_POST["FechaNacimiento"];
        $idusuario = $_POST["IdUsuario"];
        $resultado = $actualizar->Actualizar($nombre,$direccion,$correo,$telefono,$genero,$nacimiento,$idusuario,$documento);

        if ($resultado instanceof Exception) {
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";;
        } 
        else if ($resultado == true) {
            echo "<script>
                alert('Cliente actualizado exitosamente');
                window.location.href='LogicaCliente.php?consultar-cliente=2';
            </script>";
        } 
        else {
            echo "<script>
                alert('Error al actualizar');
                window.location.href='LogicaCliente.php?consultar-cliente=2';
            </script>";
        }
    }

    else if (isset($_GET["actualizar-cliente"]) && $_GET["actualizar-cliente"] == "4" && isset($_GET["documento"])) {
    //Actualizar

        $actualizar = new cliente();
        $usuarios = $actualizar->Consultar();
        
        if($usuarios instanceof Exception){
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        }
        else{
            $datos = null;
            foreach($usuarios as $usuario) {
                if($usuario['DocumentoIdentidad'] == $_GET["documento"]) {
                    $datos = $usuario;
                    break;
                }
            }
            
            if($datos) {
                // Guardar en sesión y redirigir
                $_SESSION['cliente_editar'] = $datos;
                header("Location: ../Vista/Administrador/Formulario_Actualizar_Cliente_Administrador.php");
                exit();
            } 
            else {
                echo "<script>
                    alert('Cliente no encontrado'); 
                    window.location.href='LogicaCliente.php?consultar-cliente=2';
                </script>";
            }
        }
    }

    else if (isset($_GET["eliminar-cliente"]) && $_GET["eliminar-cliente"] == "4"){
        //Eliminar
        
        $eliminar= new cliente();
        $devuelto = $eliminar->Eliminar($_GET["documento"]);

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
                window.location.href = '../Vista/Administrador/Consultar_Cliente_Administrador.php';
            </script>";
        }
    }
?>