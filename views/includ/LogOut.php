<script>
    let btn_salir= document.querySelector(".btn-exit-system");

    btn_salir.addEventListener('click',function(e){
        e.preventDefault();//Prevenimos el evento por defecto
        Swal.fire({
			title: 'Quieres Salir del Sistema?',
			text: "La Sesión actual se cerrará y saldrás del Sistema",
			type: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si, Salir!',
			cancelButtonText: 'No, Cancelar'
		}).then((result) => {
			if (result.value) {
				let url='<?php echo SERVERURL; ?>ajax/loginAjax.php';
                let token='<?php echo $logCont->encryption($_SESSION['token_spm']);  ?>';
                let usuario='<?php echo $logCont->encryption($_SESSION['usuario_spm']);  ?>';

                let datos = new FormData();
                /*"token" y "usuario" tienen que llamarse igual al del controlador $_POST['token'] $_POST['usuario'] en la funcion cierre_sesion_controlador() */
                datos.append("token",token);
                datos.append("usuario",usuario);

                //fetch(action,config)
                fetch(url,{
                    method: 'POST',
                    body: datos
                })
                .then(respuesta=> respuesta.json())
                .then(respuesta=> {

                return alertas_ajax(respuesta);
                });
            }
		});
    });
</script>