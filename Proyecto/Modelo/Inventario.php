<?php
    class inventario{
        public function Registrar($nombre,$precio,$stock,$descripcion,$idusuario){
            try{
                $conexion=new PDO("mysql:host=localhost;dbname=mejigrocer","root");
                $insertar=$conexion->prepare("insert into INVENTARIO(Nombre,Precio,Stock,Descripcion,IdUsuario) values(?,?,?,?,?)");
                $insertar->execute([$nombre,$precio,$stock,$descripcion,$idusuario]);
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
                I.IdInventario,
                I.Nombre,
                I.Precio,
                I.Stock,
                I.Descripcion,
                I.Estado,
                U.IdUsuario AS IdUsuarioRegistra
            FROM INVENTARIO I
            LEFT JOIN USUARIO U ON I.IdUsuario = U.IdUsuario
            WHERE I.Estado='A'
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
                I.IdInventario,
                I.Nombre,
                I.Precio,
                I.Stock,
                I.Descripcion,
                I.Estado,
                U.IdUsuario AS IdUsuarioRegistra
            FROM INVENTARIO I
            LEFT JOIN USUARIO U ON I.IdUsuario = U.IdUsuario
            WHERE I.Estado='I'
        ");
                $consulta->execute();
                $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $resultado;
            } 
            catch (Exception $error){
                return $error; 
            }
        }
        public function RestaurarInventario($idinventario) {
            try {
                $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer", "root");
                $consulta = $conexion->prepare("UPDATE INVENTARIO SET Estado = 'A' WHERE IdInventario = ?");
                $consulta->execute([$idinventario]);
                return true;
            } 
            catch (Exception $error) {
                return $error;
            }
        }
        public function Actualizar($nombre,$precio,$stock,$descripcion,$idusuario,$idinventario){
             try{
                $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer","root");
                $editar = $conexion->prepare("update INVENTARIO set Nombre=?,Precio=?,Stock=?,Descripcion=?,IdUsuario=? where IdInventario=?");
                $editar->execute([$nombre,$precio,$stock,$descripcion,$idusuario,$idinventario]);
                $respuesta=true;
                return $respuesta;
            }
            catch(Exception $error){
                return $error;
            }
        }
        public function Eliminar($idinventario){
            try{
            $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer","root");
            $cambiar = $conexion->prepare("update INVENTARIO set Estado = 'I' where IdInventario = ?");
            $cambiar->execute([$idinventario]);
            $respuesta=true;
            return $respuesta;
            }
            catch(Exception $error){
                return $error;
            }
        }
    }
?>