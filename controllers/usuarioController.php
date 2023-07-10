<?php

    if($peticionAjax){
        require_once "../models/usuarioModel.php";
    }else{
        require_once "./models/usuarioModel.php";
    }


class usuarioController extends usuarioModel{
    
    /*------ Controlador Agregar Usuario ------*/
    public function agregar_usuario_controlador(){
            $dni= mainModel::limpiar_cadena($_POST['usuario_dni_reg']);
            $nombre= mainModel::limpiar_cadena($_POST['usuario_nombre_reg']);
            $apellido= mainModel::limpiar_cadena($_POST['usuario_apellido_reg']);
            $telefono= mainModel::limpiar_cadena($_POST['usuario_telefono_reg']);
            $direccion= mainModel::limpiar_cadena($_POST['usuario_direccion_reg']);


            $usuario= mainModel::limpiar_cadena($_POST['usuario_usuario_reg']);
            $email= mainModel::limpiar_cadena($_POST['usuario_email_reg']);
            $clave1= mainModel::limpiar_cadena($_POST['usuario_clave_1_reg']);
            $clave2= mainModel::limpiar_cadena($_POST['usuario_clave_2_reg']);


            $privilegio= mainModel::limpiar_cadena($_POST['usuario_privilegio_reg']);
            
            /*== Comprobar campos vacios ==*/

            if($dni=="" || $nombre=="" || $apellido=="" || $usuario=="" || $clave1=="" || $clave2==""){
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
            if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$direccion)){

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

        if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)){

            $alerta= [
            "Alerta"=> "Simple",
            "Titulo"=> "Ocurrio un Error Inesperado",
            "Texto"=> "El NOMBRE DE USUARIO no coincide con el formato solicitado",
            "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }


        if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave1) || mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave2)){

            $alerta= [
            "Alerta"=> "Simple",
            "Titulo"=> "Ocurrio un Error Inesperado",
            "Texto"=> "Las CLAVES no coincide con el formato solicitado",
            "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        /*== Comprobando DNI Repetidos==*/

        $check_dni= mainModel::ejecutar_consulta_simple("SELECT usuario_dni FROM usuario WHERE usuario_dni='$dni'");

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

        /*== Comprobando Usuario Repetidos ==*/

        $check_user= mainModel::ejecutar_consulta_simple("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");

        if($check_user->rowCount() > 0 ){
              
            $alerta= [
            "Alerta"=> "Simple",
            "Titulo"=> "Ocurrio un Error Inesperado",
            "Texto"=> "El nombre de USUARIO ya se encuentra registrado",
            "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        /*== Comprobando EMAIL repetidos ==*/

        /* Si el campo email no es obligatorio realizar esta comprobacion antes $email !=""*/
        if($email !=""){
            if(filter_var($email,FILTER_VALIDATE_EMAIL)){//Funcion php para validar un email valido

                $check_email= mainModel::ejecutar_consulta_simple("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");

                if($check_email->rowCount() > 0 ){
                      
                    $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El EMAIL ya se encuentra registrado en el Sistema",
                    "Tipo"=> "error"
                    ];
                    echo json_encode($alerta);//Convertimos a json el array "alerta"
                    exit();
        
        
                }

            }else{

                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El EMAIL no es VALIDO",
                    "Tipo"=> "error"
                    ];
                    echo json_encode($alerta);//Convertimos a json el array "alerta"
                    exit();

            }
        }

                /*== Comprobando Claves ==*/
            if($clave1 != $clave2){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "Las CLAVES no coinciden!!",
                    "Tipo"=> "error"
                    ];
                    echo json_encode($alerta);//Convertimos a json el array "alerta"
                    exit();

            }else{

                $clave= mainModel::encryption($clave1);//encripto la clave

            }

            /*== Comprobando Privilegios ==*/
            if($privilegio<1 || $privilegio>3){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El privilegio seleccionado no es Valido!!",
                    "Tipo"=> "error"
                    ];
                    echo json_encode($alerta);//Convertimos a json el array "alerta"
                    exit();

            }

            $datos_usuario_reg= [
                "DNI"=>$dni,
                "Nombre"=>$nombre,
                "Apellido"=>$apellido,
                "Telefono"=>$telefono,
                "Direccion"=>$direccion,
                "Email"=>$email,
                "Usuario"=>$usuario,
                "Clave"=>$clave,
                "Estado"=>"Activa",
                "Privilegio"=>$privilegio

            ];

            $agregar_usuario= usuarioModel::agregar_usuario_model($datos_usuario_reg);

            if($agregar_usuario->rowCount()== 1){
                $alerta= [
                    "Alerta"=> "Limpiar",
                    "Titulo"=> "Usuario Registrado",
                    "Texto"=> "Los datos del usuario se ha registrado con exito!",
                    "Tipo"=> "success"
                    ];
                    echo json_encode($alerta);
            }else{
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "No hemos podido registrar el usuario!!",
                    "Tipo"=> "error"
                    ];
                    echo json_encode($alerta);

            }




    }/*Fin Controlador Agregar Usuario*/

    /*------ Controlador Eliminar Usuario ------*/
    public function eliminar_usuario_controlador(){
        /*Recibiendo ID del usuario*/
        $id=mainModel::decryption($_POST['usuario_id_delet']);
        $id=mainModel::limpiar_cadena($id);

        /*Comprobando el usuario principal*/
        if($id == 1){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "No se puede eliminar el usuario principal del Sistema",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        /*Comprobando el usuario en la BD*/
        $check_user= mainModel::ejecutar_consulta_simple("SELECT usuario_id FROM usuario WHERE usuario_id='$id'");
        if($check_user->rowCount()<=0){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El USUARIO que intentas eliminar NO EXISTE en el sistema",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        /*Comprobando los Prestamos*/
        $check_prestamos= mainModel::ejecutar_consulta_simple("SELECT usuario_id FROM prestamo WHERE usuario_id='$id' LIMIT 1");
        if($check_prestamos->rowCount()>0){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "No podemos eliminar este USUARIO debido a que tiene PRESTAMOS ASOCIADOS, 
                recomendamos deshabilitar el usuario si ya no sera utilizado",
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

        $eliminar_usuario= usuarioModel::eliminar_usuario_model($id);

        if($eliminar_usuario->rowCount()==1){
            $alerta= [
                "Alerta"=> "Recargar",
                "Titulo"=> "Usuario Eliminado",
                "Texto"=> "El Usuario ha sido eliminado exitosamente!!",
                "Tipo"=> "success"
            ];

        }else{
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "No hemos podido eliminar el Usuario, por favor intente nuevamente!!",
                "Tipo"=> "error"
            ];
            
        }
        echo json_encode($alerta);//Convertimos a json el array "alerta"

    }//Fin Controlador Eliminar

    /*------ Controlador Paginar Usuario ------*/
    public function paginador_usuario_controlador($pagina,$registros,$privilegio,$id,$url,$busqueda){
        $pagina= mainModel::limpiar_cadena($pagina);
        $registros= mainModel::limpiar_cadena($registros);
        $privilegio= mainModel::limpiar_cadena($privilegio);
        $id= mainModel::limpiar_cadena($id);

        $url= mainModel::limpiar_cadena($url);
        $url=SERVERURL.$url."/";

        $busqueda= mainModel::limpiar_cadena($busqueda);
        $tabla="";

        $pagina= (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
        $inicio= ($pagina>0) ? (($pagina*$registros)-$registros) : 0;

        if(isset($busqueda) && $busqueda!=""){
            $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE 
            ((usuario_id!='$id' AND usuario_id!='1') AND (usuario_dni LIKE '%$busqueda%' OR usuario_nombre LIKE '%$busqueda%' OR 
            usuario_apellido LIKE '%$busqueda%' OR usuario_telefono LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%' OR 
            usuario_usuario LIKE '%$busqueda%')) ORDER BY usuario_nombre ASC LIMIT $inicio,$registros";
        }else{
            $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE usuario_id!='$id' AND usuario_id!='1' ORDER BY usuario_nombre ASC LIMIT $inicio,$registros";
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
                    <th>USUARIO</th>
                    <th>EMAIL</th>
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
                        <td>'.$rows['usuario_dni'].'</td>
                        <td>'.$rows['usuario_nombre'].' '.$rows['usuario_apellido'].'</td>
                        <td>'.$rows['usuario_telefono'].'</td>
                        <td>'.$rows['usuario_usuario'].'</td>
                        <td>'.$rows['usuario_email'].'</td>
                        <td>
                            <a href="'.SERVERURL.'user-update/'.mainModel::encryption($rows['usuario_id']).'/" class="btn btn-success">
                                <i class="fas fa-sync-alt"></i>	
                            </a>
                        </td>
                        <td>
                            <form class="form-neon FormularioAjax" action="'.SERVERURL.'ajax/usuarioAjax.php" method="POST" data-form="delete" autocomplete="off">
                            <input type="hidden" name="usuario_id_delet" value="'.mainModel::encryption($rows['usuario_id']).'">
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
    }/*Fin Controlador paginador Usuario*/

    /*------ Controlador Datos Usuario ------*/
    public function datos_usuario_controlador($tipo,$id){
        
        $tipo=mainModel::limpiar_cadena($tipo);
        $id=mainModel::decryption($id);
        $id=mainModel::limpiar_cadena($id);

        return usuarioModel::datos_usuario_model($tipo,$id);

    }/*Fin Controlador Datos Usuario*/

    /*------ Controlador Actualizar Usuario ------*/

    public function actualizar_usuario_controlador($datos){
        //Recibiendo el ID
        $id=mainModel::decryption($_POST['usuario_id_update']);

        $id=mainModel::limpiar_cadena($id);

        //Comprobar el usuario en la BDD
        $check_user=mainModel::ejecutar_consulta_simple("SELECT * FROM usuario WHERE usuario_id='$id'");
        if($check_user->rowCount()<=0){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "NO pudimos encontrar el USUARIO solicitado",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();
        }else{
        
        $campos= $check_user->fetch();

        }

        $dni=mainModel::limpiar_cadena($_POST['usuario_dni_up']);
        $nombre=mainModel::limpiar_cadena($_POST['usuario_nombre_up']);
        $apellido=mainModel::limpiar_cadena($_POST['usuario_apellido_up']);
        $telefono=mainModel::limpiar_cadena($_POST['usuario_telefono_up']);
        $direccion=mainModel::limpiar_cadena($_POST['usuario_direccion_up']);
        
        $usuario=mainModel::limpiar_cadena($_POST['usuario_usuario_up']);
        $email=mainModel::limpiar_cadena($_POST['usuario_email_up']);

        if(isset($_POST['usuario_estado_up'])){

            $estado=mainModel::limpiar_cadena($_POST['usuario_estado_up']);


        }else{
            $estado=$campos['usuario_estado'];
            
        }

        if(isset($_POST['usuario_privilegio_up'])){

            $privilegio=mainModel::limpiar_cadena($_POST['usuario_privilegio_up']);

        }else{
            $privilegio=$campos['usuario_privilegio'];
            
        }

        $admin_usuario=mainModel::limpiar_cadena($_POST['usuario_admin']);
        $admin_clave=mainModel::limpiar_cadena($_POST['clave_admin']);
        

        $tipo_cuenta=mainModel::limpiar_cadena($_POST['tipo_cuenta']);

        /*== Comprobar campos vacios ==*/

        if($dni=="" || $nombre=="" || $apellido=="" || $usuario=="" || $admin_usuario=="" || $admin_clave==""){
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
            if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$direccion)){

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

        if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)){

            $alerta= [
            "Alerta"=> "Simple",
            "Titulo"=> "Ocurrio un Error Inesperado",
            "Texto"=> "El NOMBRE DE USUARIO no coincide con el formato solicitado",
            "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$admin_usuario)){

            $alerta= [
            "Alerta"=> "Simple",
            "Titulo"=> "Ocurrio un Error Inesperado",
            "Texto"=> "Tu NOMBRE DE USUARIO no coincide con el formato solicitado",
            "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$admin_clave)){

            $alerta= [
            "Alerta"=> "Simple",
            "Titulo"=> "Ocurrio un Error Inesperado",
            "Texto"=> "Tu CLAVE no coincide con el formato solicitado",
            "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        $admin_clave=mainModel::encryption($admin_clave);

        if($privilegio < 1 || $privilegio > 3){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El privilegio ingresado NO EXISTE",
                "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();
        }

        if($estado !="Activa" && $estado !="Deshabilitada"){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El Estado de la cuenta no coincide con el formato solicitado",
                "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();
        }

        /*== Comprobando DNI Repetidos==*/
        if($dni!= $campos['usuario_dni']){
            $check_dni= mainModel::ejecutar_consulta_simple("SELECT usuario_dni FROM usuario WHERE usuario_dni='$dni'");

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
        
        /*== Comprobando Usuario Repetidos ==*/

        if($usuario != $campos['usuario_usuario']){
            
            $check_user= mainModel::ejecutar_consulta_simple("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");

            if($check_user->rowCount() > 0 ){
                    
                $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El nombre de USUARIO ya se encuentra registrado",
                "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();
    
            }

        }

        /*== Comprobando Email ==*/
        if($email != $campos['usuario_email'] && $email!=""){

            if(filter_var($email,FILTER_VALIDATE_EMAIL)){

                $check_email= mainModel::ejecutar_consulta_simple("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");

                if($check_email->rowCount() > 0 ){
                        
                    $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El nuevo EMAIL ya se encuentra registrado",
                    "Tipo"=> "error"
                    ];
                    echo json_encode($alerta);//Convertimos a json el array "alerta"
                    exit();
        
                }
                
            }else{

                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El EMAIL ingresado no es VALIDO",
                    "Tipo"=> "error"
                    ];
                    echo json_encode($alerta);//Convertimos a json el array "alerta"
                    exit();
            }


        }

        /*== Comprobando Claves ==*/
        if($_POST['usuario_clave_nueva_1'] != "" || $_POST['usuario_clave_nueva_2']!= ""){
            if($_POST['usuario_clave_nueva_1'] != $_POST['usuario_clave_nueva_2']){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "Las NUEVAS CLAVES no coinciden",
                    "Tipo"=> "error"
                    ];
                    echo json_encode($alerta);//Convertimos a json el array "alerta"
                    exit();
            }else{

                if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$_POST['usuario_clave_nueva_1']) || mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$_POST['usuario_clave_nueva_2'])){
                    $alerta= [
                        "Alerta"=> "Simple",
                        "Titulo"=> "Ocurrio un Error Inesperado",
                        "Texto"=> "Las nuevas CONTRASEÑAS NO CUMPLEN con el formato solicitado",
                        "Tipo"=> "error"
                        ];
                        echo json_encode($alerta);//Convertimos a json el array "alerta"
                        exit();
                }

                $clave=mainModel::encryption($_POST['usuario_clave_nueva_1']);

            }

        }else{
            $clave= $campos['usuario_clave'];
        }

        /*== Comprobando Creedenciales para actualizar los datos ==*/

        if($tipo_cuenta =="Propia"){
            $check_cuenta= mainModel::ejecutar_consulta_simple("SELECT usuario_id FROM usuario WHERE usuario_usuario='$admin_usuario' AND 
            usuario_clave='$admin_clave' AND usuario_id='$id'");
        }else{
            session_start(['name'=>'SPM']);

            if($_SESSION['privilegio_spm'] != 1){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "No tienes el permiso necesario para realizar esta accion",
                    "Tipo"=> "error"
                    ];
                    echo json_encode($alerta);//Convertimos a json el array "alerta"
                    exit();

            }

            $check_cuenta= mainModel::ejecutar_consulta_simple("SELECT usuario_id FROM usuario WHERE usuario_usuario='$admin_usuario' AND 
            usuario_clave='$admin_clave'");
        }

        if($check_cuenta->rowCount()<=0){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "Nombre y Clave de Administrador no Validos",
                "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

        }

        /*== Preparando datos para enviarlos al modelo ==*/

        $datos_usuario_update=[
            "DNI"=>$dni,
            "Nombre"=>$nombre,
            "Apellido"=>$apellido,
            "Telefono"=>$telefono,
            "Direccion"=>$direccion,
            "Email"=>$email,
            "Usuario"=>$usuario,
            "Clave"=>$clave,
            "Estado"=>$estado,
            "Privilegio"=>$privilegio,
            "ID"=>$id
        ];

        if(usuarioModel::actualizar_usuario_model($datos_usuario_update)){

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

        


    }/*Fin Controlador Actualizar Usuario*/

}