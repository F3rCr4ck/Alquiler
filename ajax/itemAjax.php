<?php

 $peticionAjax = true;

 require_once "../config/app.php";

 //Si estamos enviando un item para eliminar, agregar o actualizar
    if(isset($_POST['item_codigo_reg']) || isset($_POST['item_id_delet']) || isset($_POST['item_id_update'])){
/*------ Instancia al controlador ------*/
    require_once "../controllers/itemController.php";

    $ins_item= new itemController();

        /*------ Agregar un Item ------*/
        //Si vienen definidas estas variables es para agregar un item
        if(isset($_POST['item_codigo_reg']) && isset($_POST['item_stock_reg']) && isset($_POST['item_nombre_reg'])){
            echo $ins_item->agregar_item_controlador();

        }

            /*------ Eliminar un Item ------*/
        //Si viene definida esta variable es para eliminar un Item
        if(isset($_POST['item_id_delet'])){
            echo $ins_item->eliminar_item_controlador($_POST['item_id_delet']);

        }
        /*------ Actualizar un Item ------*/
        if(isset($_POST['item_id_update'])){
            echo $ins_item->actualizar_item_controlador($_POST['item_id_update']);

        }
    
    }else{//Si no es un envio de Formulario
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }