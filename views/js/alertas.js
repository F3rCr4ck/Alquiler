const formularios_ajax= document.querySelectorAll(".FormularioAjax");//todos los formularios deben tener esa clase


function enviar_formulario_ajax(e){
    e.preventDefault();//No se va a redireccionar a la direccion por default "action"

    let data = new FormData(this);
    let method = this.getAttribute("method");
    let action = this.getAttribute("action");
    let tipo = this.getAttribute("data-form");

    let encabezados= new Headers();

    let config= {
        method: method,
        headers: encabezados,
        mode: 'cors',
        cache: 'no-cache',
        body: data
    }

    let texto_alerta;

    if(tipo =="save"){
        texto_alerta="Los Datos se guardaran en el Sistema";

    }else if(tipo =="delete"){
        texto_alerta="Los Datos seran eliminados del Sistema";

    }else if(tipo =="update"){
        texto_alerta="Los Datos del sistema seran actualizados";
    }
    else if(tipo =="search"){
        texto_alerta="Se eliminara el termino de busqueda y tendras que escribir de nuevo";
    }else if(tipo =="loans"){
        texto_alerta="Desea remover los datos seleccionados para prestamos o reservaciones";
    }else{
        texto_alerta="Quieres realizar la operacion solicitada?";
    }

    Swal.fire({
        title: 'Estas Seguro?',
        text: texto_alerta,
        type: 'questions',
        showCancelButton: true,
        confirmButtonColor: '#3085D6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar'
        
        }).then((result) => {
        if (result.value) {
            fetch(action,config)
            .then(respuesta=> respuesta.json())
            .then(respuesta=> {

                return alertas_ajax(respuesta);
            });

        }
    });


}

formularios_ajax.forEach(formularios => {

    formularios.addEventListener("submit",enviar_formulario_ajax);//Escuchando un evento a ejecutar
});

function alertas_ajax(alerta){

    if(alerta.Alerta === "Simple"){

        Swal.fire({
            title: alerta.Titulo,
            text: alerta.Texto,
            type: alerta.Tipo,
            confirmButtonText: 'Aceptar'
            
          });

    }else if(alerta.Alerta === "Recargar"){
    Swal.fire({
        title: alerta.Titulo,
        text: alerta.Texto,
        type: alerta.Tipo,
        confirmButtonText: 'Aceptar'
        }).then((result) => {
        if (result.value) {
            location.reload();
        }
        });

    }else if(alerta.Alerta === "Limpiar"){
        Swal.fire({
            title: alerta.Titulo,
            text: alerta.Texto,
            type: alerta.Tipo,
            confirmButtonText: 'Aceptar'
            }).then((result) => {
            if (result.value) {
                document.querySelector(".FormularioAjax").reset();
            }
            });
    }else if(alerta.Alerta === "Redireccionar"){
        window.location.href= alerta.URL;


    }
}