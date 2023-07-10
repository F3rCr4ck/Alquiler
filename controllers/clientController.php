<?php

    if($peticionAjax){
        require_once "../models/clientModel.php";
    }else{
        require_once "./models/clientModel.php";
    }


class clientController extends clientModel{
    
    /*------ Controlador Agregar Cliente ------*/
    public function agregar_cliente_controlador(){
            $dni= mainModel::limpiar_cadena($_POST['client_dni_reg']);
            $nombre= mainModel::limpiar_cadena($_POST['client_nombre_reg']);
            $apellido= mainModel::limpiar_cadena($_POST['client_apellido_reg']);
            $telefono= mainModel::limpiar_cadena($_POST['client_telefono_reg']);
            $direccion= mainModel::limpiar_cadena($_POST['client_direccion_reg']);
            
            /*== Comprobar campos vacios Obligatorios ==*/

            if($dni=="" || $nombre=="" || $apellido=="" || $telefono=="" || $direccion==""){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "No has llenado todos los campos obligatorios",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();
            }

            /*== Verificando integridad de los Datos==*/
            //pattern="" en la vista " caracteres{min,max}"

            if(mainModel::verificar_datos("[0-9-]{8,8}",$dni)){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El DNI no coincide con el formato solicitado",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

            }

            if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El NOMBRE no coincide con el formato solicitado",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

            }

            if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El APELLIDO no coincide con el formato solicitado",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

            }

            //Puede venir vacia la variable por no ser obligatorio, por eso la comprobacion antes de verificar
            if($telefono !=""){
                if(mainModel::verificar_datos("[0-9()+]{8,20}",$telefono)){

                    $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El TELEFONO no coincide con el formato solicitado",
                    "Tipo"=> "error"
                    ];
                    echo json_encode($alerta);//Convertimos a json el array "alerta"
                    exit();

            }
        }

        //Puede venir vacia la variable por no ser obligatorio, por eso la comprobacion antes de verificar
        if($direccion !=""){
            if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}",$direccion)){

                $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "La DIRECCION no coincide con el formato solicitado",
                "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

        }
        }


        /*== Comprobando DNI Repetidos==*/

        $check_dni= mainModel::ejecutar_consulta_simple("SELECT cliente_dni FROM cliente WHERE cliente_dni='$dni'");

        if($check_dni->rowCount() > 0 ){
              
            $alerta= [
            "Alerta"=> "Simple",
            "Titulo"=> "Ocurrio un Error Inesperado",
            "Texto"=> "El DNI ya se encuentra registrado",
            "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();


        }

            $datos_cliente_reg= [
                "DNI"=>$dni,
                "Nombre"=>$nombre,
                "Apellido"=>$apellido,
                "Telefono"=>$telefono,
                "Direccion"=>$direccion

            ];

            $agregar_cliente= clientModel::agregar_cliente_model($datos_cliente_reg);

            if($agregar_cliente->rowCount()== 1){
                $alerta= [
                    "Alerta"=> "Limpiar",
                    "Titulo"=> "Usuario Registrado",
                    "Texto"=> "Los datos del Cliente se ha registrado con exito!",
                    "Tipo"=> "success"
                    ];
                    echo json_encode($alerta);
            }else{
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "No hemos podido registrar el Cliente!!",
                    "Tipo"=> "error"
                    ];
                    echo json_encode($alerta);

            }




    }/*Fin Controlador Agregar Cliente*/

       /*------ Controlador Eliminar Cliente ------*/
       public function eliminar_cliente_controlador(){
        /*Recibiendo ID del usuario*/
        $id= $_POST['client_id_delet'];
        $id=mainModel::limpiar_cadena($id);

        /*Comprobando el usuario en la BD*/
        $check_client= mainModel::ejecutar_consulta_simple("SELECT cliente_id FROM cliente WHERE cliente_id='$id'");
        if($check_client->rowCount()<=0){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El CLIENTE que intentas eliminar NO EXISTE en el sistema",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        /*Comprobando los Prestamos*/
        $check_prestamos= mainModel::ejecutar_consulta_simple("SELECT cliente_id FROM prestamo WHERE cliente_id='$id' LIMIT 1");
        if($check_prestamos->rowCount()>0){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "No podemos eliminar este CLIENTE debido a que tiene PRESTAMOS ASOCIADOS",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        /*Comprobando los Privilegios*/
        session_start(['name'=>'SPM']);
        if($_SESSION['privilegio_spm']!= 1){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "No tienes los Permisos necesarios para realizar esta Accion",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();
        }

        $eliminar_cliente= clientModel::eliminar_cliente_model($id);

        if($eliminar_cliente->rowCount()==1){
            $alerta= [
                "Alerta"=> "Recargar",
                "Titulo"=> "CLIENTE Eliminado",
                "Texto"=> "El Cliente ha sido eliminado exitosamente!!",
                "Tipo"=> "success"
            ];

        }else{
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "No hemos podido eliminar el Cliente, por favor intente nuevamente!!",
                "Tipo"=> "error"
            ];
            
        }
        echo json_encode($alerta);//Convertimos a json el array "alerta"

    }//Fin Controlador Eliminar

    /*------ Controlador Paginar Cliente ------*/
    public function paginador_cliente_controlador($pagina,$registros,$url,$busqueda){
        $pagina= mainModel::limpiar_cadena($pagina);
        $registros= mainModel::limpiar_cadena($registros);

        $url= mainModel::limpiar_cadena($url);
        $url=SERVERURL.$url."/";

        $busqueda= mainModel::limpiar_cadena($busqueda);
        $tabla="";

        $pagina= (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
        $inicio= ($pagina>0) ? (($pagina*$registros)-$registros) : 0;

        if(isset($busqueda) && $busqueda!=""){
            $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM cliente WHERE (cliente_dni LIKE '%$busqueda%' OR cliente_nombre LIKE '%$busqueda%' OR 
            cliente_apellido LIKE '%$busqueda%' OR cliente_telefono LIKE '%$busqueda%' OR cliente_direccion LIKE '%$busqueda%') ORDER BY cliente_nombre ASC LIMIT $inicio,$registros";
        }else{
            $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM cliente ORDER BY cliente_nombre ASC LIMIT $inicio,$registros";
        }

        $conexion= mainModel::conectar();
        $datos= $conexion->query($consulta);

        $datos= $datos->fetchAll();
        

        $total= $conexion->query("SELECT FOUND_ROWS()");
        $total= (int) $total->fetchColumn();

        $Npaginas= ceil($total/$registros);

        $tabla.='<div class="table-responsive">
            <table class="table table-dark table-sm">
                <thead>
                <tr class="text-center roboto-medium">
                    <th>#</th>
                    <th>DNI</th>
                    <th>NOMBRE</th>
                    <th>TELÉFONO</th>
                    <th>DIRECCION</th>
                    <th>ACTUALIZAR</th>
                    <th>ELIMINAR</th>
                </tr>
            </thead>
            <tbody>';

            if($total>=1 && $pagina<=$Npaginas){
                $contador=$inicio+1;

                $reg_inicio= $inicio+1;

                foreach($datos as $rows){
                    $tabla.='
                    <tr class="text-center" >
                        <td>'.$contador.'</td>
                        <td>'.$rows['cliente_dni'].'</td>
                        <td>'.$rows['cliente_nombre'].' '.$rows['cliente_apellido'].'</td>
                        <td>'.$rows['cliente_telefono'].'</td>
                        <td>'.$rows['cliente_direccion'].'</td>
                        <td>
                            <a href="'.SERVERURL.'client-update/'.$rows['cliente_id'].'/" class="btn btn-success">
                                <i class="fas fa-sync-alt"></i>	
                            </a>
                        </td>
                        <td>
                            <form class="form-neon FormularioAjax" action="'.SERVERURL.'ajax/clienteAjax.php" method="POST" data-form="delete" autocomplete="off">
                            <input type="hidden" name="client_id_delet" value="'.$rows['cliente_id'].'">
                                <button type="submit" class="btn btn-warning">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                </tr>';
                $contador++;

                }
                $reg_final=$contador-1;
                
            }else{
                if($total>=1){
                    $tabla.='<tr class="text-center"><td colspan="9"> <a href="'.$url.'" class="btn btn-raised btn-primary btn-sm">Haga Click aca para recargar el listado</a></td> </tr>';
                }else{
                    $tabla.='<tr class="text-center"><td colspan="9"> No Hay registros en el sistema</td> </tr>';
                }
                
            }
        $tabla.='</tbody>
                </table>
                </div>';
    
            if($total>=1 && $pagina<=$Npaginas){
                $tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,5);
                $tabla.='<p class="text-right">Mostrando Usuarios '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
            }
    return $tabla;
    }/*Fin Controlador paginador Cliente*/

    /*------ Controlador Datos Cliente ------*/
    public function datos_cliente_controlador($tipo,$id){
        
        $tipo= mainModel::limpiar_cadena($tipo);

        $id= mainModel::decryption($id);
        $id=mainModel::limpiar_cadena($id);


        return clientModel::datos_cliente_model($tipo,$id);

    }/*Fin Controlador Datos Cliente*/


    /*------ Controlador Actualizar Cliente ------*/
    public function actualizar_cliente_controlador($datos){
        //Recibiendo el ID
        $id=$_POST['client_id_update'];

        $id=mainModel::limpiar_cadena($id);

        //Comprobar el usuario en la BDD
        $check_client=mainModel::ejecutar_consulta_simple("SELECT * FROM cliente WHERE cliente_id='$id'");
        if($check_client->rowCount()<=0){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "NO pudimos encontrar el CLIENTE solicitado",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();
        }else{
        
        $campos= $check_client->fetch();

        }

        $dni=mainModel::limpiar_cadena($_POST['cliente_dni_up']);
        $nombre=mainModel::limpiar_cadena($_POST['cliente_nombre_up']);
        $apellido=mainModel::limpiar_cadena($_POST['cliente_apellido_up']);
        $telefono=mainModel::limpiar_cadena($_POST['cliente_telefono_up']);
        $direccion=mainModel::limpiar_cadena($_POST['cliente_direccion_up']);


        /*== Comprobar campos vacios ==*/

        if($dni=="" || $nombre=="" || $apellido=="" || $telefono=="" || $direccion==""){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "No has llenado todos los campos obligatorios",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();
        }
        
        /*== Verificando integridad de los Datos==*/
            //pattern="" en la vista " caracteres{min,max}"

            if(mainModel::verificar_datos("[0-9-]{8,8}",$dni)){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El DNI no coincide con el formato solicitado",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

            }

            if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,35}",$nombre)){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El NOMBRE no coincide con el formato solicitado",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

            }

            if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,35}",$apellido)){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El APELLIDO no coincide con el formato solicitado",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

            }

            //Puede venir vacia la variable por no ser obligatorio, por eso la comprobacion antes de verificar
            if($telefono !=""){
                if(mainModel::verificar_datos("[0-9()+]{8,20}",$telefono)){

                    $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El TELEFONO no coincide con el formato solicitado",
                    "Tipo"=> "error"
                    ];
                    echo json_encode($alerta);//Convertimos a json el array "alerta"
                    exit();

            }
        }

        //Puede venir vacia la variable por no ser obligatorio, por eso la comprobacion antes de verificar
        if($direccion !=""){
            if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}",$direccion)){

                $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "La DIRECCION no coincide con el formato solicitado",
                "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

        }
        }

        /*== Comprobando DNI Repetidos==*/
        if($dni!= $campos['cliente_dni']){
            $check_dni= mainModel::ejecutar_consulta_simple("SELECT cliente_dni FROM cliente WHERE cliente_dni='$dni'");

            if($check_dni->rowCount() > 0 ){
                    
                $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El DNI ya se encuentra registrado",
                "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();
            }

        }

        /*== Preparando datos para enviarlos al modelo ==*/

        $datos_cliente_update=[
            "DNI"=>$dni,
            "Nombre"=>$nombre,
            "Apellido"=>$apellido,
            "Telefono"=>$telefono,
            "Direccion"=>$direccion,
            "ID"=>$id
        ];

        if(clientModel::actualizar_cliente_model($datos_cliente_update)){

            $alerta= [
                "Alerta"=> "Recargar",
                "Titulo"=> "Datos Actualizados con EXITO",
                "Texto"=> "Los Datos han sido Actualizados correctamente",
                "Tipo"=> "success"
                ];
        }else{
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "No hemos podido actualizar los datos, por favor intente nuevamente",
                "Tipo"=> "error"
                ];
    
        }

        echo json_encode($alerta);//Convertimos a json el array "alerta"

        


    }/*Fin Controlador Actualizar Cliente*/
}