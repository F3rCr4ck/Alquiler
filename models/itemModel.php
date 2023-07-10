<?php

require_once "mainModel.php";

class itemModel extends mainModel{

    /*------ Modelo Agregar Item ------*/
    protected static function agregar_item_model($datos){
        $sql= mainModel::conectar()->prepare("INSERT INTO item(item_codigo,item_nombre,item_stock,item_estado,
        item_detalle) VALUES(:Codigo,:Nombre,:Stock,:Estado,:Detalle)");
        
        //bindParam(":Marcador",$datos['Indice']) 
        $sql->bindParam(":Codigo",$datos['Codigo']);//Para poder Sustituir los marcadores
        $sql->bindParam(":Nombre",$datos['Nombre']);
        $sql->bindParam(":Stock",$datos['Stock']);
        $sql->bindParam(":Estado",$datos['Estado']);
        $sql->bindParam(":Detalle",$datos['Detalle']);

        $sql->execute();

        return $sql;
        
    }//Fin de agregar_item_model

    /*------ Modelo Eliminar Item ------*/
    protected static function eliminar_item_model($id){
        $sql=mainModel::conectar()->prepare("DELETE FROM item WHERE item_id=:ID");
        $sql->bindParam(":ID",$id);

        $sql->execute();

        return $sql;

    }


    /*------ Modelo Datos Item ------*/

    protected static function datos_item_model($tipo,$id){

        if($tipo=="Unico"){
            $sql=mainModel::conectar()->prepare("SELECT * FROM item WHERE item_id=:ID");
            $sql->bindParam(":ID",$id);
        }elseif($tipo=="Conteo"){
            $sql=mainModel::conectar()->prepare("SELECT item_id FROM item");
            
        }

        $sql->execute();

        return $sql;

    }

    /*------ Modelo Actualizar Item ------*/

    protected static function actualizar_item_model($datos){

        $sql=mainModel::conectar()->prepare("UPDATE item SET item_codigo=:Codigo, item_nombre=:Nombre, item_stock=:Stock,
                                                item_estado=:Estado,item_detalle=:Detalle WHERE item_id=:ID");

        $sql->bindParam(":Codigo",$datos['Codigo']);//Para poder Sustituir los marcadores
        $sql->bindParam(":Nombre",$datos['Nombre']);
        $sql->bindParam(":Stock",$datos['Stock']);
        $sql->bindParam(":Estado",$datos['Estado']);
        $sql->bindParam(":Detalle",$datos['Detalle']);
        $sql->bindParam(":ID",$datos['ID']);
 
        $sql->execute();

        return $sql;
    }


}