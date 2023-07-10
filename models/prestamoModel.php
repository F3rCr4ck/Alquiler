<?php

    require_once "mainModel.php";

    class prestamoModel extends mainModel{
        /*------ Modelo Agregar Prestamo ------*/
        protected static function agregar_prestamo_model($datos){
            $sql= mainModel::conectar()->prepare("INSERT INTO prestamo(prestamo_codigo,prestamo_fecha_inicio,prestamo_hora_inicio,prestamo_fecha_final,
            prestamo_hora_final,prestamo_cantidad,prestamo_total,prestamo_pagado,prestamo_estado,prestamo_observacion,usuario_id,cliente_id) VALUES(:Codigo,:FechaInicio,:HoraInicio,
            :FechaFinal,:HoraFinal,:Cantidad,:Total,:Pagado,:Estado,:Observacion,:Usuario,:Cliente)");
            
            //bindParam(":Marcador",$datos['Indice']) 
            $sql->bindParam(":Codigo",$datos['Codigo']);//Para poder Sustituir los marcadores
            $sql->bindParam(":FechaInicio",$datos['FechaInicio']);
            $sql->bindParam(":HoraInicio",$datos['HoraInicio']);
            $sql->bindParam(":FechaFinal",$datos['FechaFinal']);
            $sql->bindParam(":HoraFinal",$datos['HoraFinal']);
            $sql->bindParam(":Cantidad",$datos['Cantidad']);
            $sql->bindParam(":Total",$datos['Total']);
            $sql->bindParam(":Pagado",$datos['Pagado']);
            $sql->bindParam(":Estado",$datos['Estado']);
            $sql->bindParam(":Observacion",$datos['Observacion']);
            $sql->bindParam(":Usuario",$datos['Usuario']);
            $sql->bindParam(":Cliente",$datos['Cliente']);

            $sql->execute();

            return $sql;
            
        }//Fin de agregar_prestamo_model

        /*------ Modelo Agregar Detalle ------*/
        protected static function agregar_detalle_model($datos){
            $sql= mainModel::conectar()->prepare("INSERT INTO detalle(detalle_cantidad,detalle_formato,detalle_tiempo,detalle_costo_tiempo,detalle_descripcion,prestamo_codigo,item_id) 
            VALUES(:Cantidad,:Formato,:Tiempo,:Costo,:Descripcion,:CodPrestamo,:ItemID)");
            
            //bindParam(":Marcador",$datos['Indice']) 
            $sql->bindParam(":Cantidad",$datos['Cantidad']);//Para poder Sustituir los marcadores
            $sql->bindParam(":Formato",$datos['Formato']);
            $sql->bindParam(":Tiempo",$datos['Tiempo']);
            $sql->bindParam(":Costo",$datos['Costo']);
            $sql->bindParam(":Descripcion",$datos['Descripcion']);
            $sql->bindParam(":CodPrestamo",$datos['CodPrestamo']);
            $sql->bindParam(":ItemID",$datos['ItemID']);
            

            $sql->execute();

            return $sql;
            
        }//Fin de agregar_detalle_model

        /*------ Modelo Agregar Pago ------*/
        protected static function agregar_pago_model($datos){
            $sql= mainModel::conectar()->prepare("INSERT INTO pago(pago_total,pago_fecha,prestamo_codigo) 
            VALUES(:Total,:Fecha,:CodPrestamo)");
            
            //bindParam(":Marcador",$datos['Indice']) 
            $sql->bindParam(":Total",$datos['Total']);//Para poder Sustituir los marcadores
            $sql->bindParam(":Fecha",$datos['Fecha']);
            $sql->bindParam(":CodPrestamo",$datos['CodPrestamo']);
            

            $sql->execute();

            return $sql;
            
        }//Fin de agregar_pago_model

        /*------ Eliminar Prestamo del Sistema ------*/
        protected static function eliminar_prestamo_model($codigo,$tipo){
            if($tipo=="Prestamo"){
                $sql= mainModel::conectar()->prepare("DELETE FROM prestamo WHERE prestamo_codigo=:CodPrestamo");
            }else if($tipo=="Detalle"){
                $sql= mainModel::conectar()->prepare("DELETE FROM detalle WHERE prestamo_codigo=:CodPrestamo");
            }else if($tipo=="Pago"){
                $sql= mainModel::conectar()->prepare("DELETE FROM pago WHERE prestamo_codigo=:CodPrestamo");
            }

            $sql->bindParam(":CodPrestamo",$codigo);
            $sql->execute();

            return $sql;

        }

        /*------ Modelo Datos Prestamo ------*/
        protected static function datos_prestamo_model($tipo,$id){
            if($tipo=="Unico"){
                $sql= mainModel::conectar()->prepare("SELECT * FROM prestamo WHERE prestamo_id=:ID");
                $sql->bindParam(":ID",$id);
            }else if($tipo=="Conteo_Reservacion"){
                $sql= mainModel::conectar()->prepare("SELECT prestamo_id FROM prestamo WHERE prestamo_estado='Reservacion'");
            }else if($tipo=="Conteo_Prestamos"){
                $sql= mainModel::conectar()->prepare("SELECT prestamo_id FROM prestamo WHERE prestamo_estado='Prestamo'");
            }else if($tipo=="Conteo_Finalizados"){
                $sql= mainModel::conectar()->prepare("SELECT prestamo_id FROM prestamo WHERE prestamo_estado='Finalizado'");
            }else if($tipo=="Conteo"){
                $sql= mainModel::conectar()->prepare("SELECT prestamo_id FROM prestamo");
            }else if($tipo=="Detalle"){
                $sql= mainModel::conectar()->prepare("SELECT * FROM detalle WHERE prestamo_codigo=:Codigo");
                $sql->bindParam(":Codigo",$id);
            }else if($tipo=="Pago"){
                $sql= mainModel::conectar()->prepare("SELECT * FROM pago WHERE prestamo_codigo=:Codigo");
                $sql->bindParam(":Codigo",$id);
            }
            $sql->execute();

            return $sql;

        }

        /*------ Modelo Actualizar Datos Prestamo ------*/
        protected static function actualizar_prestamo_model($datos){
            if($datos['Tipo']=="Pago"){
                $sql= mainModel::conectar()->prepare("UPDATE prestamo SET prestamo_pagado=:Monto WHERE prestamo_codigo=:Codigo");
                $sql->bindParam(":Monto",$datos['Monto']);
            }else if($datos['Tipo']=="Prestamo"){
                $sql= mainModel::conectar()->prepare("UPDATE prestamo SET prestamo_estado=:Estado,prestamo_observacion=:Observacion WHERE prestamo_codigo=:Codigo");
                $sql->bindParam(":Estado",$datos['Estado']);
                $sql->bindParam(":Observacion",$datos['Observacion']);
                
            }
            $sql->bindParam(":Codigo",$datos['Codigo']);

            $sql->execute();

            return $sql;

        }
        
    }