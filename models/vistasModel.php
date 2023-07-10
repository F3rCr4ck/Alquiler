<?php
/*Nos va a permitir obtener las vistas que se van a ir mostrando en el index.php*/
 class vistasModel{
    /*------ Modelo para Obtener Vistas ------*/
    protected static function obtener_vistas_modelo($vistas){
        $listaBlanca=["home","client-list","client-new","client-search","client-update","company","user-list","user-new",
        "user-search","user-update","reservation-list","reservation-new","reservation-pending","reservation-reservation",
        "reservation-update","reservation-search","item-list","item-new","item-search","item-update"];
        //Comprobaremos la lista permitida que tendremos en nuestro sistema
        if(in_array($vistas, $listaBlanca)){
            if(is_file("./views/content/".$vistas."-view.php")){//Comprueba si esta el archivo y muestra
                $contenido= "./views/content/".$vistas."-view.php";
            }else{
                $contenido= "404";
            }

        }else if($vistas== "login" || $vistas== "index"){
            $contenido= "login";            

        }else{
            $contenido= "404";
        }


        return $contenido;



    }
 }