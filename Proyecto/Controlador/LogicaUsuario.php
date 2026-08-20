<?php
session_start();
include "../Modelo/Usuario.php";

// Inicializar contador e informacion de bloqueo si no existen
    if(!isset($_SESSION['intentos'])){
        $_SESSION['intentos'] = 0;
    }
    if(!isset($_SESSION['bloqueo'])){
        $_SESSION['bloqueo'] = 0;
    }
// Verificar si el usuario está bloqueado
    if(time() < $_SESSION['bloqueo']){
        $restantes = $_SESSION['bloqueo'] - time();
        echo "<script>
            alert('Has excedido 3 intentos. Intenta de nuevo en $restantes segundos.');
            window.location.href='../Vista/InicioSesion/Login.html';
        </script>";
        exit(); 
    } 
    else {
    // Reiniciar intentos si ya pasó el tiempo de bloqueo
        if($_SESSION['bloqueo'] > 0){
            $_SESSION['intentos'] = 0;
            $_SESSION['bloqueo'] = 0;
        }
    }

// Verificar formulario de login
    if(isset($_POST['inicio']) && $_POST['inicio'] == "0") {
        $usuario = new usuario();
        $devuelto = $usuario->Acceso($_POST['NombreUsuario'], $_POST['Contrasena']);

        if($devuelto instanceof Exception){
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
            exit();
        }

        if($devuelto){
            $_SESSION['IdUsuario']  = isset($devuelto['IdUsuario']) ? $devuelto['IdUsuario'] : (isset($devuelto['id']) ? $devuelto['id'] : null);
            $_SESSION['IdUsuario'] = $devuelto['IdUsuario'];
            $_SESSION['TipoUsuario'] = $devuelto['TipoUsuario'];
            $_SESSION['mi_usuario_fulldata'] = $usuario->ConsultarPorId($devuelto['IdUsuario']);
            $_SESSION['intentos'] = 0; // Reiniciar intentos al entrar
            $_SESSION['bloqueo'] = 0; // Reiniciar bloqueo
            
                $datosCompletos = $usuario->ConsultarPorId($devuelto['IdUsuario']);
                $tipousu = $devuelto['TipoUsuario']; 
            switch($tipousu){
                case 'Administrador':
                    header("Location: ../Vista/Administrador/Pagina_Empresa_Cliente_Administrador.html");
                    exit();
                case 'Cajero':
                    header("Location: ../Vista/Cajero/Pagina_Empresa_Cliente_Cajero.html");
                    exit();
                case 'OperarioBodega':
                    header("Location: ../Vista/OperarioBodega/Pagina_Empresa_Cliente_OperarioBodega.html");
                    exit();
            }
        
    } 
    else {
            $_SESSION['intentos'] += 1;

            if($_SESSION['intentos'] >= 3){
                // Bloqueo por 30 segundos
                $_SESSION['bloqueo'] = time() + 30;
                echo "<script>
                    alert('Has excedido 3 intentos. Intenta de nuevo en 30 segundos.');
                    window.location.href='../Vista/InicioSesion/Login.html';
                </script>";
                exit();
            } else {
                // Mostrar intentos restantes 
                $restantes = 3 - $_SESSION['intentos'];
                echo "<script>
                    alert('Datos incorrectos. Te quedan $restantes intento(s).');
                    window.location.href='../Vista/InicioSesion/Login.html';
                </script>";
                exit();
            }
        }
    }
    
    else if (isset($_POST["registrar-usu"]) && $_POST["registrar-usu"] == "1"){
        //Registrar

            $archivo_tmp = isset($_FILES["Certificacion"]['tmp_name']) ? $_FILES["Certificacion"]['tmp_name'] : '';
            //tmp_name: nombre temporal del archivo ?->si la condicion es verdadera :->si la condicion es falsa
            $archivo_nombre = isset($_FILES["Certificacion"]['name']) ? $_FILES["Certificacion"]['name'] : '';
            //isset: si una variable está definida y no es null.
            
            $directorio_destino = "../Archivos";

            if (!file_exists($directorio_destino)) {
                mkdir($directorio_destino, 0777, true); 
                //crea una carpeta, la carpeta,parametros sistema operativo,crea la carpeta si no existe
            }
            
            $nuevo_nombre = uniqid() . "_" . $archivo_nombre;//uniquid()identificador unico
            $ruta_archivo = $directorio_destino ."/". $nuevo_nombre;
            
            move_uploaded_file($archivo_tmp, $ruta_archivo);

            $registro=new usuario;
            $devuelto=$registro->Registrar($_POST["NombreUsuario"],$_POST["Contrasena"],$_POST["TipoUsuario"],$_POST["Nombre"],$_POST["Direccion"],$_POST["Correo"],$_POST["FechaNacimiento"],$_POST["Titulo"],$_POST["NivelEducativo"],$ruta_archivo,$_POST["HabilidadesAdicionales"],$_POST["NombreEmpresa"],$_POST["CargoQueOcupaba"],$_POST["Duracion"],$_POST["NombreJefe"],$_POST["TelefonoJefe"]);
            if($devuelto instanceof Exception){
                echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
            }
            else if($devuelto === "duplicado"){
                $_SESSION['mensaje'] = 'Usuario existente, reintentelo';
                $_SESSION['tipo_mensaje'] = 'duplicado';
                header("Location: ../Vista/Administrador/Registrar_Usuario_Administrador.php");
                exit();
            }
            else if($devuelto){
                //Evita que los archivos se dupliquen
                $_SESSION['mensaje'] = 'Usuario creado de manera exitosa';
                $_SESSION['tipo_mensaje'] = 'success';
                header("Location: ../Vista/Administrador/Registrar_Usuario_Administrador.php");
                exit();
            }
            else{
                $_SESSION['mensaje'] = 'Los datos no corresponden, intente de nuevo';
                $_SESSION['tipo_mensaje'] = 'error';
                header("Location: ../Vista/Administrador/Registrar_Usuario_Administrador.php");
                exit();
            }
    }

    else if (isset($_POST["consultar-mi-usuario"]) && $_POST["consultar-mi-usuario"] == "2") {
   
        $usuario = new usuario();

        // ID del usuario logueado
        $idusuario = $_SESSION['IdUsuario'];

        // Consultar solo su usuario
        $datos = $usuario->ConsultarPorId($idusuario);
        if($datos instanceof Exception){
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        }
        if(!empty($datos)) {
            $_SESSION['mi_usuario_fulldata'] = $datos;
        } else {
            echo "<script>alert('No se encontró el usuario'); window.location.href='../Vista/InicioSesion/Login.html';</script>";
            exit();
        }

        header("Location: ../Vista/Consultar_Usuario.php");
        exit();
    }

    else if (isset($_POST["actualizar-mi-usuario"])&& $_POST["actualizar-mi-usuario"] == "3") {
        
        $usuario = new usuario();

        $idusuario = $_POST['IdUsuario'];
        $nombreusuario = $_POST['NombreUsuario'];
        $contrasena = $_POST['Contrasena'];
        $tipousuario = $_POST["TipoUsuario"];
        $nombre1 = $_POST["Nombre"];
        $direccion1 = $_POST["Direccion"];
        $correo1 = $_POST["Correo"];
        $fechanacimiento1 = $_POST["FechaNacimiento"];
        $titulo = $_POST["Titulo"];
        $nivel = $_POST["NivelEducativo"];
        
        // Manejo del archivo
        $certificacion = isset($_POST["Certificacion_actual"]) ? $_POST["Certificacion_actual"] : '';
        
        if(isset($_FILES['Certificacion']) && $_FILES['Certificacion']['error'] == 0) {
            $directorio = "../Archivos";
            if(!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }
            $nombre_archivo = uniqid() . "_" . $_FILES['Certificacion']['name'];
            $ruta_destino = $directorio . "/" . $nombre_archivo; 
            
            if(move_uploaded_file($_FILES['Certificacion']['tmp_name'], $ruta_destino)) {
                $certificacion = $ruta_destino;
            }
        }
        
        $habilidades = $_POST["HabilidadesAdicionales"];
        $empresa = $_POST["NombreEmpresa"];
        $cargo = $_POST["CargoQueOcupaba"];
        $duracion = $_POST["Duracion"];
        $nombrejefe = $_POST["NombreJefe"];
        $telefonojefe = $_POST["TelefonoJefe"];

        $resultado = $usuario->ActualizarMiUsuario($idusuario,$nombreusuario,$contrasena,$tipousuario,$nombre1,$direccion1,$correo1,$fechanacimiento1,$titulo,$nivel,$certificacion,$habilidades,$empresa,$cargo,$duracion,$nombrejefe,$telefonojefe);

        if ($resultado instanceof Exception) {
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        } 
        else if ($resultado) {
            // Actualizar los datos en sesión después de la actualización
            $datosActualizados = $usuario->ConsultarPorId($idusuario);
            if ($datosActualizados && !($datosActualizados instanceof Exception)) {
                $_SESSION['mi_usuario_fulldata'] = $datosActualizados;
            }
            else if(!empty($datosActualizados)) {
                $_SESSION['mi_usuario_fulldata'] = $datosActualizados[0];
            }
            echo "<script>alert('Datos actualizados correctamente'); window.location.href='../Vista/Consultar_Usuario.php';</script>";
        } 
        else {
            echo "<script>alert('No se pudo actualizar'); window.location.href='../Vista/Consultar_Usuario.php';</script>";
        }
    }
   
    else if (isset($_GET["actualizar-mi-usuario"]) && $_GET["actualizar-mi-usuario"] == "4" && isset($_GET["id"])) {
    //Actualizar

        $actualizar = new usuario();
        $idusuario = $_SESSION['IdUsuario'];
        $usuario = $actualizar->ConsultarPorId($idusuario);
        
        if($usuario instanceof Exception){
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        }
        else{
            $datos = $usuario;
            }
            
            if($datos) {
                // Guardar en sesión y redirigir
                $_SESSION['mi_usuario_fulldata'] = $datos;
                header("Location: ../Vista/Formulario_Actualizar_Mi_Usuario.php");
                exit();
            } 
            else {
                echo "<script>
                    alert('Usuario no encontrado'); 
                    window.location.href='../Vista/Consultar_Usuario.php'
                </script>";
            }
    }

    else if(isset($_GET["consultar-usu"]) && $_GET["consultar-usu"] == "2"){
        //Consultar
        
        $consulta = new usuario();
        $datos = $consulta->Consultar();

        if($datos instanceof Exception){
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        }
        else{
            $_SESSION['usuarios'] = $datos;
            echo "<script>
                    window.location.href='../Vista/Administrador/Consultar_Usuario_Administrador.php'
                </script>";
        }
    }
    else if (isset($_GET["inactivos"]) && $_GET["inactivos"] == "2") {

        $consulta = new usuario();
        $devuelto = $consulta->ConsultarInactivos();

        if ($devuelto instanceof Exception) {
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        } 
        else {
            $_SESSION['usuarios'] = $devuelto;
            header("Location: ../Vista/Administrador/Consultar_Usuario_Administrador.php?inactivos=2");
            exit();
        }
    }
    else if (isset($_GET["restaurar"]) && $_GET["restaurar"] == "2" && isset($_GET["id"])) {

        $idusuario = $_GET["id"];
        $usuario = new usuario();
        $resultado = $usuario->RestaurarUsuario($idusuario);
        if ($resultado instanceof Exception) {
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        } 
        else if ($resultado === true) {
            echo "<script>
                    alert('Usuario restaurado correctamente');
                    window.location.href='../Vista/Administrador/Consultar_Usuario_Administrador.php?inactivos=2';
                </script>";
        } else {
            echo "<script>
                    alert('Error al restaurar el usuario');
                    window.location.href='../Vista/Administrador/Consultar_Usuario_Administrador.php?inactivos=2';
                </script>";
        }
    }

    else if (isset($_POST["actualizar-usu"]) && $_POST["actualizar-usu"] == "3") {
        
        $actualizar = new usuario();

        $idusuario = $_POST["IdUsuario"];
        $nombreusuario = $_POST["NombreUsuario"];
        $contra = $_POST["Contrasena"];
        $tipousuario = $_POST["TipoUsuario"];
        $nombre1 = $_POST["Nombre"];
        $direccion1 = $_POST["Direccion"];
        $correo1 = $_POST["Correo"];
        $fechanacimiento1 = $_POST["FechaNacimiento"];
        $titulo = $_POST["Titulo"];
        $nivel = $_POST["NivelEducativo"];
        
        // Manejo del archivo
        $certificacion = isset($_POST["Certificacion_actual"]) ? $_POST["Certificacion_actual"] : '';
        
        if(isset($_FILES['Certificacion']) && $_FILES['Certificacion']['error'] == 0) {
            $directorio = "../Archivos";
            if(!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }
            $nombre_archivo = uniqid() . "_" . $_FILES['Certificacion']['name'];
            $ruta_destino = $directorio . "/" . $nombre_archivo; 
            
            if(move_uploaded_file($_FILES['Certificacion']['tmp_name'], $ruta_destino)) {
                $certificacion = $ruta_destino;
            }
        }
        
        $habilidades = $_POST["HabilidadesAdicionales"];
        $empresa = $_POST["NombreEmpresa"];
        $cargo = $_POST["CargoQueOcupaba"];
        $duracion = $_POST["Duracion"];
        $nombrejefe = $_POST["NombreJefe"];
        $telefonojefe = $_POST["TelefonoJefe"];

        $resultado = $actualizar->Actualizar($idusuario,$nombreusuario,$contra,$tipousuario,$nombre1,$direccion1,$correo1,$fechanacimiento1,$titulo,$nivel,$certificacion,$habilidades,$empresa,$cargo,$duracion,$nombrejefe,$telefonojefe);

        if ($resultado instanceof Exception) {
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";;
        } 
        else if ($resultado == true) {
            echo "<script>
                alert('Usuario actualizado exitosamente');
                window.location.href='LogicaUsuario.php?consultar-usu=2';
            </script>";
        } 
        else {
            echo "<script>
                alert('Error al actualizar');
                window.location.href='LogicaUsuario.php?consultar-usu=2';
            </script>";
        }
    }
    else if (isset($_GET["actualizar-usu"]) && $_GET["actualizar-usu"] == "4" && isset($_GET["id"])) {
    //Actualizar

        $actualizar = new usuario();
        $usuarios = $actualizar->Consultar();
        
        if($usuarios instanceof Exception){
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        }
        else{
            $datos = null;
            foreach($usuarios as $usuario) {
                if($usuario['IdUsuario'] == $_GET["id"]) {
                    $datos = $usuario;
                    break;
                }
            }
            
            if($datos) {
                // Guardar en sesión y redirigir
                $_SESSION['usuario_editar'] = $datos;
                header("Location: ../Vista/Administrador/Formulario_Actualizar_Usuario_Administrador.php");
                exit();
            } 
            else {
                echo "<script>
                    alert('Usuario no encontrado'); 
                    window.location.href='LogicaUsuario.php?consultar-usu=2';
                </script>";
            }
        }
    }

    else if (isset($_GET["eliminar-usu"]) && $_GET["eliminar-usu"] == "4"){
        //Eliminar
        
        $eliminar= new usuario();
        $devuelto = $eliminar->Eliminar($_GET["id"]);

        if($devuelto instanceof Exception){
            echo "<img src='https://stonkstutors.com/wp-content/uploads/2023/07/Error-500.jpg'>";
        }
        else{
            echo "<script>alert('Eliminacion exitosa');
                window.location.href = '../Vista/Administrador/Consultar_Usuario_Administrador.php';
            </script>";
        }
    }    
?>