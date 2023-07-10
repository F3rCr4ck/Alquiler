<?php 

require_once "./models/vistasModel.php";//incluimos el archivo para poder heredarlo y utilizar los modelos


class vistasController extends vistasModel{
    /*------ Controlador para Obtener la plantilla ------*/
    public function obtener_plantilla_controlador(){

        return require_once "./views/plantilla.php";
    }

    /*------ Controlador para Obtener las Vistas------*/
    public function obtener_vistas_controlador(){
        if(isset($_GET['views'])){
            $ruta= explode("/",$_GET['views']);//divide un string de acuerdo a delimitador que le asignamos. Separamos los que viene en $_GET

            $respuesta= vistasModel::obtener_vistas_modelo($ruta[0]);
        }else{
            $respuesta= "login"; //Sino viene nada mostramos el login
        }

        return $respuesta;

    }

}