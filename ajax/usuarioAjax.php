<?php

 $peticionAjax = true;

 require_once "../config/app.php";

 //Si estamos enviando un usuario para eliminar, agregar o actualizar
    if(isset($_POST['usuario_dni_reg']) || isset($_POST['usuario_id_delet']) || isset($_POST['usuario_id_update'])){
/*------ Instancia al controlador ------*/
    require_once "../controllers/usuarioController.php";

    $ins_usuario= new usuarioController();

        /*------ Agregar un Usuario ------*/
        //Si vienen definidas estas dos variables es para agregar un usuario
        if(isset($_POST['usuario_dni_reg']) && isset($_POST['usuario_nombre_reg'])){
            echo $ins_usuario->agregar_usuario_controlador();

        }

            /*------ Eliminar un Usuario ------*/
        //Si viene definida esta variable es para eliminar un usuario
        if(isset($_POST['usuario_id_delet'])){
            echo $ins_usuario->eliminar_usuario_controlador($_POST['usuario_id_delet']);

        }
        /*------ Actualizar un Usuario ------*/
        if(isset($_POST['usuario_id_update'])){
            echo $ins_usuario->actualizar_usuario_controlador($_POST['usuario_id_update']);

        }
    
    }else{//Si no es un envio de Formulario
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }