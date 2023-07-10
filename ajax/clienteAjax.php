<?php

 $peticionAjax = true;

 require_once "../config/app.php";

 //Si estamos enviando un Cliente para eliminar, agregar o actualizar
    if(isset($_POST['client_dni_reg']) || isset($_POST['client_id_delet']) || isset($_POST['client_id_update'])){
/*------ Instancia al controlador ------*/
    require_once "../controllers/clientController.php";

    $ins_cliente= new clientController();

        /*------ Agregar un Cliente ------*/
        //Si vienen definidas estas dos variables es para agregar un usuario
        if(isset($_POST['client_dni_reg']) && isset($_POST['client_nombre_reg'])){
            echo $ins_cliente->agregar_cliente_controlador();

        }

            /*------ Eliminar un Cliente ------*/
        //Si viene definida esta variable es para eliminar un usuario
        if(isset($_POST['client_id_delet'])){
            echo $ins_cliente->eliminar_cliente_controlador($_POST['client_id_delet']);

        }
        /*------ Actualizar un Cliente ------*/
        if(isset($_POST['client_id_update'])){
            echo $ins_cliente->actualizar_cliente_controlador($_POST['client_id_update']);

        }
    
    }else{//Si no es un envio de Formulario
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }