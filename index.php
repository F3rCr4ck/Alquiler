<?php

    require_once "./config/app.php";//Las configuraciones generales
    require_once "./controllers/vistasController.php";


    $plantilla = new vistasController(); //instanciamos
    $plantilla->obtener_plantilla_controlador();