<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>
        <?php echo COMPANY; ?>
    </title>
    <?php include "./views/includ/link.php"; ?>
</head>
<body>
    <?php
        $peticionAjax=false;
	    require_once "./controllers/vistasController.php";
        $IV = new vistasController(); //Instancia a la Vista
        $vistas= $IV->obtener_vistas_controlador();//resultado del controlador

        if($vistas == "login" || $vistas == "404"){

            require_once "./views/content/".$vistas."-view.php";
        }else{
            session_start(['name'=>'SPM']);

            $pagina= explode("/", $_GET['views']);
            require_once "./controllers/loginController.php";

            $logCont = new loginController();

            /*Si no viene definida ninguna variable de sesion vamos a cerrar la sesion*/
            if(!isset($_SESSION['token_spm']) || !isset($_SESSION['usuario_spm']) || !isset($_SESSION['privilegio_spm']) || !isset($_SESSION['id_spm'])){
                echo $logCont->forzar_cierre_sesion_controlador();
                exit();
            }

            //$logCont->forzar_cierre_sesion_controlador();
    ?>
	<!-- Main container -->
	<main class="full-box main-container">
    <!-- Nav lateral -->
    <?php include "./views/includ/navAside.php"; ?>

		<!-- Page content -->
		<section class="full-box page-content">
        <!-- Navbar-->
        <?php 
            include "./views/includ/navbar.php"; 
            include $vistas; //En el modelo devolvemos la ruta de la vista
        ?>  


		</section>
	</main>
        
	<?php
    include "./views/includ/LogOut.php";
    }
    include "./views/includ/script.php"; 
    
    ?> 
</body>
</html>