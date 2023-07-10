<?php

 $peticionAjax = true;

 require_once "../config/app.php";

 //Si estamos enviando un usuario para eliminar, agregar o actualizar
    if(isset($_POST['buscar_cliente']) || isset($_POST['id_agregar_cliente']) || isset($_POST['id_eliminar_cliente']) || isset($_POST['buscar_item'])
    || isset($_POST['id_agregar_item']) || isset($_POST['id_eliminar_item']) || isset($_POST['prestamo_fecha_inicio_reg']) || isset($_POST['prestamo_codigo_delet'])
    || isset($_POST['pago_codigo_reg']) || isset($_POST['prestamo_codigo_up'])
    ){
    /*------ Instancia al controlador ------*/
    require_once "../controllers/prestamoController.php";

    $ins_prestamo= new prestamoController();

    /*------ Buscar Cliente ------*/
    if(isset($_POST['buscar_cliente'])){
        echo $ins_prestamo->buscar_cliente_prestamo_controlador();

    }

    /*------ Agregar Cliente ------*/
    if(isset($_POST['id_agregar_cliente'])){
        echo $ins_prestamo->agregar_cliente_prestamo_controlador();

    }

    /*------ Eliminar Cliente ------*/
    if(isset($_POST['id_eliminar_cliente'])){
            echo $ins_prestamo->eliminar_cliente_prestamo_controlador();
    }

    /*------ Buscar Item ------*/
    if(isset($_POST['buscar_item'])){
        echo $ins_prestamo->buscar_item_prestamo_controlador();

    }

    /*------ Agregar Item ------*/
    if(isset($_POST['id_agregar_item'])){
        echo $ins_prestamo->agregar_item_prestamo_controlador();

    }

    /*------ Eliminar Item ------*/
    if(isset($_POST['id_eliminar_item'])){
        echo $ins_prestamo->eliminar_item_prestamo_controlador();
    }

    /*------ Agregar Prestamo ------*/
    if(isset($_POST['prestamo_fecha_inicio_reg'])){
        echo $ins_prestamo->agregar_prestamo_controlador();

    }

    /*------ Eliminar Prestamo ------*/
    if(isset($_POST['prestamo_codigo_delet'])){
        echo $ins_prestamo->eliminar_prestamo_controlador();
    }

    /*------ Agregar Pago ------*/
    if(isset($_POST['pago_codigo_reg'])){
        echo $ins_prestamo->agregar_pago_controlador();
    }

    /*------ Actualizar Prestamo ------*/
    if(isset($_POST['prestamo_codigo_up'])){
        echo $ins_prestamo->actualizar_prestamo_controlador();
    }

    
    }else{//Si no es un envio de Formulario
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }