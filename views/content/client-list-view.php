<!-- Page header -->
<div class="full-box page-header">
<h3 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CLIENTES
</h3>
<p class="text-justify">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit nostrum rerum animi natus beatae ex. Culpa blanditiis tempore amet alias placeat, obcaecati quaerat ullam, sunt est, odio aut veniam ratione.
</p>
</div>

<div class="container-fluid">
<ul class="full-box list-unstyled page-nav-tabs">
    <li>
        <a href="<?php echo SERVERURL;?>client-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CLIENTE</a>
    </li>
    <li>
        <a class="active" href="<?php echo SERVERURL;?>client-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CLIENTES</a>
    </li>
    <li>
        <a href="<?php echo SERVERURL;?>client-search"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CLIENTE</a>
    </li>
</ul>	
</div>

<!-- Content here-->
<div class="container-fluid">
<?php 
			require_once "./controllers/clientController.php";

			$ins_cliente= new clientController();

			//paginador_usuario_controlador($pagina,$registros,$id,$url,$busqueda);
			echo $ins_cliente->paginador_cliente_controlador($pagina[1],15,$pagina[0],"");
		?>
</div>