<?php
class venta{
    public function Registrar($datosVenta,$productosSeleccionados){
        try{
            $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer","root");
            $insertar=$conexion->prepare("insert into VENTA (Fecha,Hora,Cantidad,SubTotal,IVA,ValorTotal,IdUsuario,DocumentoIdentidad) values(curdate(),curtime(),?,?,?,?,?,?)");
            $insertar->execute([
                $datosVenta["Cantidad"],
                $datosVenta["SubTotal"],
                $datosVenta["IVA"],
                $datosVenta["ValorTotal"],
                $datosVenta["IdUsuario"],
                $datosVenta["DocumentoIdentidad"]
            ]);
            $idventa=$conexion->lastInsertId();
            //insertar productos vendidos en la tabla INCLUYE
            foreach($productosSeleccionados as $produc){
                $detalle=$conexion->prepare("insert into INCLUYE (IdInventario,IdVenta,PrecioProducto,CantidadVendida) values(?,?,?,?)");
                $detalle->execute([$produc["IdInventario"],$idventa,$produc["PrecioProducto"],$produc["CantidadVendida"]]);
                //Actualizar stock en inventario
            $stock=$conexion->prepare("update INVENTARIO set Stock=Stock-? where IdInventario=?");
            $stock->execute([$produc["CantidadVendida"],$produc["IdInventario"]]);
            }
            
            $respuesta=true;
            return $respuesta;
        }
        catch(Exception $error){
            return $error;
        }
    }
    public function Consultar(){
        try{
            $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer", "root");
            $consulta=$conexion->prepare("
            SELECT
                V.IdVenta,
                V.Fecha,
                V.Hora,
                V.Cantidad,
                V.SubTotal,
                V.IVA,
                V.ValorTotal,
                V.Estado,
                U.IdUsuario AS IdUsuarioQueRegistra,
                C.DocumentoIdentidad AS DocumentoCliente
            FROM VENTA V
            LEFT JOIN USUARIO U ON V.IdUsuario = U.IdUsuario
            LEFT JOIN CLIENTE C ON V.DocumentoIdentidad = C.DocumentoIdentidad
            WHERE V.Estado='A'");
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        }
        catch(Exception $error){
            return $error;
        }
}
    public function ConsultarPorFecha($fecha){
        try{
            $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer", "root");
            $consulta=$conexion->prepare("
            SELECT
                V.IdVenta,
                V.Fecha,
                V.Hora,
                V.Cantidad,
                V.SubTotal,
                V.IVA,
                V.ValorTotal,
                V.Estado,
                U.IdUsuario AS IdUsuarioQueRegistra,
                C.DocumentoIdentidad AS DocumentoCliente
            FROM VENTA V
            LEFT JOIN USUARIO U ON V.IdUsuario = U.IdUsuario
            LEFT JOIN CLIENTE C ON V.DocumentoIdentidad = C.DocumentoIdentidad
            WHERE V.Estado='A' AND V.Fecha=?");
            $consulta->execute([$fecha]);
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
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
                V.IdVenta,
                V.Fecha,
                V.Hora,
                V.Cantidad,
                V.SubTotal,
                V.IVA,
                V.ValorTotal,
                V.Estado,
                U.IdUsuario AS IdUsuarioQueRegistra,
                C.DocumentoIdentidad AS DocumentoCliente
            FROM VENTA V
            LEFT JOIN USUARIO U ON V.IdUsuario = U.IdUsuario
            LEFT JOIN CLIENTE C ON V.DocumentoIdentidad = C.DocumentoIdentidad
            WHERE V.Estado='I'
        ");
                $consulta->execute();
                $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $resultado;
            } 
            catch (Exception $error){
                return $error; 
            }
        }
    public function RestaurarVenta($idventa) {
            try {
                $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer", "root");
                $consulta = $conexion->prepare("UPDATE VENTA SET Estado = 'A' WHERE IdVenta = ?");
                $consulta->execute([$idventa]);
                return true;
            } 
            catch (Exception $error) {
                return $error;
            }
        }
        public function Eliminar($idventa){
            try{
            $conexion = new PDO("mysql:host=localhost;dbname=mejigrocer","root");
            $cambiar = $conexion->prepare("update VENTA set Estado = 'I' where IdVenta = ?");
            $cambiar->execute([$idventa]);
            $respuesta=true;
            return $respuesta;
            }
            catch(Exception $error){
                return $error;
            }
        }
    }
?>