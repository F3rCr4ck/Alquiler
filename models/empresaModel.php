<?php

    require_once "mainModel.php";

    class empresaModel extends mainModel{
        
            /*------ Modelo Datos Empresa ------*/
    protected static function datos_empresa_model(){

        $sql=mainModel::conectar()->prepare("SELECT * FROM empresa");
        
        $sql->execute();

        return $sql;

    }


    }