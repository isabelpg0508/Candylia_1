<?php
    class usuario{
        public function Acceso($nombreusu,$contra){
            try{
                $conexion= new PDO("mysql:host=localhost;dbname=mejigrocer","root");
                $consulta=$conexion->prepare("select IdUsuario,NombreUsuario,Contrasena,TipoUsuario,Estado from USUARIO where NombreUsuario=? and Contrasena=? and Estado='A'");
                $consulta->execute([$nombreusu,$contra]);
                $respuesta=$consulta->fetch(PDO::FETCH_ASSOC); //fetch: Consulta a partir de cada fila,fetch_Assoc:Organiza los datos utilizando los nombres de cada campo
                return $respuesta;
            }
            catch (Exception $error){
                return $error;
            }
        }
        public function Registrar($nombreusuario,$contra,$tipousuario,$nombre1,$direccion1,$correo1,$fechanacimiento1,$titulo,$nivel,$certificacion,$habilidades,$empresa,$cargo,$duracion,$nombrejefe,$telefonojefe){
            try{
                $conexion=new PDO("mysql:host=localhost;dbname=mejigrocer","root");
                $insertar=$conexion->prepare("insert into USUARIO(NombreUsuario,Contrasena,TipoUsuario,Nombre,Direccion,Correo,FechaNacimiento) values(?,?,?,?,?,?,?)");
                $insertar->execute([$nombreusuario,$contra,$tipousuario,$nombre1,$direccion1,$correo1,$fechanacimiento1]);
                //Id autogenerado del usuario
                $idusuario=$conexion->lastInsertId();
                //Tabla Formacion Academica
                $insertar=$conexion->prepare("insert into FormacionAcademica(Titulo,NivelEducativo,Certificacion,HabilidadesAdicionales,IdUsuario) values(?,?,?,?,?)");
                $insertar->execute([$titulo,$nivel,$certificacion,$habilidades,$idusuario]);
                //Tabla Experiencia Laboral
                $insertar=$conexion->prepare("insert into ExperienciaLaboral(NombreEmpresa,CargoQueOcupaba,Duracion,NombreJefe,TelefonoJefe,IdUsuario) values(?,?,?,?,?,?)");
                $insertar->execute([$empresa,$cargo,$duracion,$nombrejefe,$telefonojefe,$idusuario]);
                $respuesta=true;
                return $respuesta;
            }
            catch(Exception $error){
                if($error->getCode() == 23000){
                        return "duplicado"; // Retorna identificador de duplicado
                    }
                    return $error;
           }
        }
       public function ConsultarPorId($idusuario){
            try{
                $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer","root");
                $consulta = $conexion->prepare("
                    SELECT 
                        U.IdUsuario, U.NombreUsuario, U.Contrasena, U.TipoUsuario, 
                        U.Nombre, U.Direccion, U.Correo, U.FechaNacimiento,
                        F.Titulo, F.NivelEducativo, F.Certificacion, F.HabilidadesAdicionales,
                        E.NombreEmpresa, E.CargoQueOcupaba, E.Duracion, E.NombreJefe, E.TelefonoJefe
                    FROM USUARIO U
                    LEFT JOIN FormacionAcademica F ON U.IdUsuario = F.IdUsuario
                    LEFT JOIN ExperienciaLaboral E ON U.IdUsuario = E.IdUsuario
                    WHERE U.IdUsuario = ?
                    LIMIT 1 
                ");//LIMIT 1 para que solo traiga 1 fila
                $consulta->execute([$idusuario]);
                $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                return $resultado;
            } catch(Exception $error){
                return $error;
            }
        }
        public function ActualizarMiUsuario($idusuario,$nombreusuario,$contrasena,$tipousuario,$nombre1,$direccion1,$correo1,$fechanacimiento1,$titulo,$nivel,$certificacion,$habilidades,$empresa,$cargo,$duracion,$nombrejefe,$telefonojefe){
            try{
                $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer","root");
                $editar1 = $conexion->prepare("update USUARIO set NombreUsuario=?,Contrasena=?,TipoUsuario=?,Nombre=?,Direccion=?,Correo=?,FechaNacimiento=? where IdUsuario=?");
                $editar1->execute([$nombreusuario,$contrasena,$tipousuario,$nombre1,$direccion1,$correo1,$fechanacimiento1,$idusuario]);
                $editar2 =$conexion->prepare("update FormacionAcademica set Titulo=?,NivelEducativo=?,Certificacion=?,HabilidadesAdicionales=? where IdUsuario=?");
                $editar2->execute([$titulo,$nivel,$certificacion,$habilidades,$idusuario]);
                $editar3=$conexion->prepare("update ExperienciaLaboral set NombreEmpresa=?,CargoQueOcupaba=?,Duracion=?,NombreJefe=?,TelefonoJefe=? where IdUsuario=?");
                $editar3->execute([$empresa,$cargo,$duracion,$nombrejefe,$telefonojefe,$idusuario]);
                $respuesta=true;
                return $respuesta;
            } 
            catch(Exception $error){
                return $error;
            }
        }
        public function Consultar(){
            try{
                $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer","root");
                $consulta = $conexion->prepare("select USUARIO.IdUsuario,USUARIO.NombreUsuario,USUARIO.Contrasena,USUARIO.TipoUsuario,USUARIO.Nombre,USUARIO.Direccion,USUARIO.Correo,USUARIO.FechaNacimiento,FormacionAcademica.Titulo,FormacionAcademica.NivelEducativo,FormacionAcademica.Certificacion,FormacionAcademica.HabilidadesAdicionales,ExperienciaLaboral.NombreEmpresa,ExperienciaLaboral.CargoQueOcupaba,ExperienciaLaboral.Duracion,ExperienciaLaboral.NombreJefe,ExperienciaLaboral.TelefonoJefe from USUARIO LEFT JOIN FormacionAcademica ON USUARIO.IdUsuario=FormacionAcademica.IdUsuario LEFT JOIN ExperienciaLaboral ON USUARIO.IdUsuario=ExperienciaLaboral.IdUsuario where USUARIO.Estado='A'");
                $consulta->execute();
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            }
            catch(Exception $error){
                return $error;
            }
        }
        public function ConsultarInactivos(){
            try {
                $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer", "root");
                $consulta = $conexion->prepare("  
                SELECT U.*, 
                F.Titulo, F.NivelEducativo, F.Certificacion, F.HabilidadesAdicionales,
                E.NombreEmpresa, E.CargoQueOcupaba, E.Duracion, E.NombreJefe, E.TelefonoJefe
                FROM USUARIO U
                LEFT JOIN FormacionAcademica F ON U.IdUsuario = F.IdUsuario
                LEFT JOIN ExperienciaLaboral E ON U.IdUsuario = E.IdUsuario
                WHERE U.Estado = 'I'");
                $consulta->execute();
                $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $resultado;
            } 
            catch (Exception $error){
                return $error; 
            }
        }
        public function RestaurarUsuario($idusuario) {
            try {
                $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer", "root");
                $consulta = $conexion->prepare("UPDATE USUARIO SET Estado = 'A' WHERE IdUsuario = ?");
                $consulta->execute([$idusuario]);
                return true;
            } 
            catch (Exception $error) {
                return $error;
            }
        }
        public function Actualizar($idusuario,$nombreusuario,$contra,$tipousuario,$nombre1,$direccion1,$correo1,$fechanacimiento1,$titulo,$nivel,$certificacion,$habilidades,$empresa,$cargo,$duracion,$nombrejefe,$telefonojefe){
            try{
                $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer","root");
                $editar1 = $conexion->prepare("update USUARIO set NombreUsuario=?,Contrasena=?,TipoUsuario=?,Nombre=?,Direccion=?,Correo=?,FechaNacimiento=? where IdUsuario=?");
                $editar1->execute([$nombreusuario,$contra,$tipousuario,$nombre1,$direccion1,$correo1,$fechanacimiento1,$idusuario]);
                $editar2 =$conexion->prepare("update FormacionAcademica set Titulo=?,NivelEducativo=?,Certificacion=?,HabilidadesAdicionales=? where IdUsuario=?");
                $editar2->execute([$titulo,$nivel,$certificacion,$habilidades,$idusuario]);
                $editar3=$conexion->prepare("update ExperienciaLaboral set NombreEmpresa=?,CargoQueOcupaba=?,Duracion=?,NombreJefe=?,TelefonoJefe=? where IdUsuario=?");
                $editar3->execute([$empresa,$cargo,$duracion,$nombrejefe,$telefonojefe,$idusuario]);
                $respuesta=true;
                return $respuesta;
            }
            catch(Exception $error){
                return $error;
            }
        }
        public function Eliminar($idusuario){
            try{
            $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer","root");
            $cambiar = $conexion->prepare("update USUARIO set Estado = 'I' where IdUsuario = ?");
            $cambiar->execute([$idusuario]);
            $respuesta=true;
            return $respuesta;
            }
            catch(Exception $error){
                return $error;
            }
        }
    }
?>