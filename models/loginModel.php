<?php

    require_once "mainModel.php";

    class loginModel extends mainModel{
    /*------ Modelo Iniciar Sesion ------*/
    protected static function iniciar_sesion_model($datos){
        $sql= mainModel::conectar()->prepare("SELECT * FROM usuario WHERE usuario_usuario= :Usuario AND usuario_clave= :Clave AND usuario_estado= 'Activa'");

        //bindParam(:Marcador,$datos['Indice'])
        $sql->bindParam(":Usuario",$datos['Usuario']);
        $sql->bindParam(":Clave",$datos['Clave']);

        $sql->execute();

        return $sql;

    }


    }