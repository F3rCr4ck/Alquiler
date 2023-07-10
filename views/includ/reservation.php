
<script>
    /*------ Buscar Cliente ------*/
    function buscar_cliente(){
        let input_cliente= document.querySelector('#input_cliente').value;
        input_cliente= input_cliente.trim();

        if(input_cliente != ""){
            let datos = new FormData();
            datos.append("buscar_cliente",input_cliente);
            fetch("<?php echo SERVERURL; ?>ajax/prestamoAjax.php",{
                method:'POST',
                body:datos
            })
            .then(respuesta=> respuesta.text())
            .then(respuesta=> {
                let tabla_clientes= document.querySelector('#tabla_clientes');
                tabla_clientes.innerHTML=respuesta;
                
            });
        }else{
            Swal.fire({
            title: 'OCURRIO UN ERROR',
            text: 'Debes introducir el DNI,Nombre,Apellido,Telefono',
            type: 'error',
            confirmButtonText: 'Aceptar'
            
          });
        }
    }

    /*------ Agregar Cliente ------*/
    function agregar_cliente(id){
    
        $('#ModalCliente').modal('hide');
    
                Swal.fire({
            title: 'Quieres agregar este Cliente?',
            text: 'Se va agregar este cliente para realizar un prestamo',
            type: 'questions',
            showCancelButton: true,
            confirmButtonColor: '#3085D6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Agregar',
            cancelButtonText: 'No, Cancelar'

            }).then((result) => {
            if (result.value){
                let datos = new FormData();
                datos.append("id_agregar_cliente",id);
                fetch("<?php echo SERVERURL; ?>ajax/prestamoAjax.php",{
                    method:'POST',
                    body:datos
                })
                .then(respuesta=> respuesta.json())
                .then(respuesta=> {
                    return alertas_ajax(respuesta);  
                });

            }else{
                $('#ModalCliente').modal('show');
            }
            });


    }

    /*------ Buscar Item ------*/
    function buscar_item(){

        let input_item= document.querySelector('#input_item').value;
        input_item= input_item.trim();//Quitar espacios

        if(input_item != ""){
            let datos = new FormData();
            datos.append("buscar_item",input_item);
            fetch("<?php echo SERVERURL; ?>ajax/prestamoAjax.php",{
                method:'POST',
                body:datos
            })
            .then(respuesta=> respuesta.text())
            .then(respuesta=> {
                let tabla_items= document.querySelector('#tabla_items');
                tabla_items.innerHTML=respuesta;
                
            });
        }else{
            Swal.fire({
            title: 'OCURRIO UN ERROR',
            text: 'Debes introducir el Codigo o el Nombre del Item',
            type: 'error',
            confirmButtonText: 'Aceptar'
            
          });
        }

    }

    /*------ Agregar Item ------*/
    function modal_agregar_item(id){
        
        $('#ModalItem').modal('hide');//Oculto el modal
        $('#ModalAgregarItem').modal('show');
        
        document.querySelector('#id_agregar_item').setAttribute("value",id);

    }
    
    /*------ Buscar  Item------*/
    function modal_buscar_item(){
        $('#ModalAgregarItem').modal('hide');//Oculto el modal
        $('#ModalItem').modal('show');

    }
</script>