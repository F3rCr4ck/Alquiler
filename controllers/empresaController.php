<?php

    if($peticionAjax){
        require_once "../models/empresaModel.php";
    }else{
        require_once "./models/empresaModel.php";
    }

    class empresaController extends empresaModel{
        public function datos_empresa_controlador(){

        return empresaModel::datos_empresa_model();

        }/*Fin Controlador Datos Prestamo*/
    
    }