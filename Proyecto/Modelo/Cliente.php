<?php
    class cliente{
        public function Registrar($documento,$nombre,$direccion,$correo,$telefono,$genero,$nacimiento,$idusuario){
            try{
                $conexion=new PDO("mysql:host=localhost;dbname=mejigrocer","root");
                $insertar=$conexion->prepare("insert into CLIENTE(DocumentoIdentidad,Nombre,Direccion,Correo,Telefono,Genero,FechaNacimiento,IdUsuario) values(?,?,?,?,?,?,?,?)");
                $insertar->execute([$documento,$nombre,$direccion,$correo,$telefono,$genero,$nacimiento,$idusuario]);
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
        public function Consultar(){
            try{
                $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer","root");
                  $consulta = $conexion->prepare("
            SELECT 
                C.DocumentoIdentidad,
                C.Nombre,
                C.Direccion,
                C.Correo,
                C.Telefono,
                C.Genero,
                C.FechaNacimiento,
                C.Estado,
                C.IdUsuario AS IdUsuarioRegistra
                
            FROM CLIENTE C
            LEFT JOIN USUARIO U ON C.IdUsuario = U.IdUsuario
            WHERE C.Estado='A'
        ");
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
            SELECT 
                C.DocumentoIdentidad,
                C.Nombre,
                C.Direccion,
                C.Correo,
                C.Telefono,
                C.Genero,
                C.FechaNacimiento,
                C.Estado,
                C.IdUsuario AS IdUsuarioRegistra
            FROM CLIENTE C
            LEFT JOIN USUARIO U ON C.IdUsuario = U.IdUsuario
            WHERE C.Estado='I'
        ");
                $consulta->execute();
                $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $resultado;
            } 
            catch (Exception $error){
                return $error; 
            }
        }
        public function RestaurarCliente($documento) {
            try {
                $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer", "root");
                $consulta = $conexion->prepare("UPDATE CLIENTE SET Estado = 'A' WHERE DocumentoIdentidad = ?");
                $consulta->execute([$documento]);
                return true;
            } 
            catch (Exception $error) {
                return $error;
            }
        }
        public function Actualizar($nombre,$direccion,$correo,$telefono,$genero,$nacimiento,$idusuario,$documento){
             try{
                $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer","root");
                $editar = $conexion->prepare("update CLIENTE set Nombre=?,Direccion=?,Correo=?,Telefono=?,Genero=?,FechaNacimiento=?,IdUsuario=? where DocumentoIdentidad=?");
                $editar->execute([$nombre,$direccion,$correo,$telefono,$genero,$nacimiento,$idusuario,$documento]);
                $respuesta=true;
                return $respuesta;
            }
            catch(Exception $error){
                return $error;
            }
        }
        public function Eliminar($documento){
            try{
            $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer","root");
            $cambiar = $conexion->prepare("update CLIENTE set Estado = 'I' where DocumentoIdentidad = ?");
            $cambiar->execute([$documento]);
            $respuesta=true;
            return $respuesta;
            }
            catch(Exception $error){
                return $error;
            }
        }
    }
?>