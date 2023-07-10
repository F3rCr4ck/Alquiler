<?php

require_once "../config/app.php";
session_start(['name'=> 'SPM']);

if(isset($_POST['busqueda_inicial']) || isset($_POST['eliminar_busqueda']) || isset($_POST['fecha_inicio']) || isset($_POST['fecha_final'])){
    $data_url= [
        "usuario"=>"user-search",
        "cliente"=>"client-search",
        "item"=>"item-search",
        "prestamo"=>"reservation-search"
        
    ];

    if(isset($_POST['modulo'])){
        $modulo=$_POST['modulo'];
        //Si el array no viene definido
        if(!isset($data_url[$modulo])){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "NO podemos continuar con la busqueda debido a un error",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();
        }
    }else{
        $alerta= [
            "Alerta"=> "Simple",
            "Titulo"=> "Ocurrio un Error Inesperado",
            "Texto"=> "NO podemos continuar con la busqueda debido a un error de configuracion",
            "Tipo"=> "error"
        ];
        echo json_encode($alerta);//Convertimos a json el array "alerta"
        exit();
    }

    if($modulo=="prestamo"){
        $fecha_inicio= "fecha_inicio_".$modulo;
        $fecha_final= "fecha_final_".$modulo;

        //Iniciar Busqueda
        if(isset($_POST['fecha_inicio']) || isset($_POST['fecha_final'])){
            if($_POST['fecha_inicio']=="" || $_POST['fecha_final']==""){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "Por Favor introduce una fecha de INICIO y una fecha FINAL",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

            }

            $_SESSION[$fecha_inicio]= $_POST['fecha_inicio'];
            $_SESSION[$fecha_final]= $_POST['fecha_final'];
        }

        //Eliminar Busqueda
        if(isset($_POST['eliminar_busqueda'])){

            unset($_SESSION[$fecha_inicio]);//elimina el valor
            unset($_SESSION[$fecha_final]);

        }
        
    }else{

        $name_var="busqueda_".$modulo;

        //Iniciar Busqueda
        if(isset($_POST['busqueda_inicial'])){
            if($_POST['busqueda_inicial']== "")
            {
                //No introdujo texto
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "Por favor introduce un texto en el Buscador",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();
            }

            $_SESSION[$name_var]= $_POST['busqueda_inicial'];

        }

        //Eliminar Busqueda
        if(isset($_POST['eliminar_busqueda'])){
            unset($_SESSION[$name_var]);//elimina el valor
            
        }

    }

    //Redireccionar
    $url= $data_url[$modulo];

    $alerta=[
        "Alerta"=>"Redireccionar",
        "URL"=>SERVERURL.$url."/"
    ];

    echo json_encode($alerta);

}else{
    session_unset();
    session_destroy();
    header("Location: ".SERVERURL."login/");
    exit();
}