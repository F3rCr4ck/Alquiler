<?php

 $peticionAjax = true;

 require_once "../config/app.php";

 if(isset($_POST['token']) && isset($_POST['usuario'])){
    /*------ Instancia al controlador ------*/
    require_once "../controllers/loginController.php";

    $ins_login= new loginController();

    echo $ins_login->cierre_sesion_controlador();

 }else{
    session_start(['name'=>'SPM']);
    session_unset();//vaciar sesion
    session_destroy();
    header("Location: ".SERVERURL."login/");
    exit();

 }