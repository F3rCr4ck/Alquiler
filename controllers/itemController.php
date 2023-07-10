<?php

if($peticionAjax){
    require_once "../models/itemModel.php";
}else{
    require_once "./models/itemModel.php";
}


class itemController extends itemModel{

    /*------ Controlador Agregar Item ------*/
    public function agregar_item_controlador(){
        $codigo= mainModel::limpiar_cadena($_POST['item_codigo_reg']);
        $nombre= mainModel::limpiar_cadena($_POST['item_nombre_reg']);
        $stock= mainModel::limpiar_cadena($_POST['item_stock_reg']);
        $estado= mainModel::limpiar_cadena($_POST['item_estado_reg']);
        $detalle= mainModel::limpiar_cadena($_POST['item_detalle_reg']);
        
        /*== Comprobar campos vacios ==*/

        if($codigo=="" || $nombre=="" || $stock=="" || $estado==""){
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

        if(mainModel::verificar_datos("[0-9-]{4,45}",$codigo)){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El CODIGO no coincide con el formato solicitado",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        if(mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}",$nombre)){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El NOMBRE no coincide con el formato solicitado",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        if(mainModel::verificar_datos("[0-9]{1,9}",$stock)){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El STOCK no coincide con el formato solicitado",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

            /*== Comprobando Estados ==*/
            if($estado != "Habilitado" && $estado != "Deshabilitado"){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El Estado seleccionado no es Valido!!",
                    "Tipo"=> "error"
                    ];
                    echo json_encode($alerta);//Convertimos a json el array "alerta"
                    exit();

            }

        //Puede venir vacia la variable por no ser obligatorio, por eso la comprobacion antes de verificar
        if($detalle !=""){
            if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$detalle)){

                $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El DETALLE no coincide con el formato solicitado",
                "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

            }
         }



        /*== Comprobando CODIGO Repetidos==*/

        $check_codigo= mainModel::ejecutar_consulta_simple("SELECT item_codigo FROM item WHERE item_codigo='$codigo'");

        if($check_codigo->rowCount() > 0 ){
                
            $alerta= [
            "Alerta"=> "Simple",
            "Titulo"=> "Ocurrio un Error Inesperado",
            "Texto"=> "El CODIGO ya se encuentra registrado",
            "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();
        }

            $datos_item_reg= [
                "Codigo"=>$codigo,
                "Nombre"=>$nombre,
                "Stock"=>$stock,
                "Estado"=>$estado,
                "Detalle"=>$detalle

            ];

        $agregar_item= itemModel::agregar_item_model($datos_item_reg);

        if($agregar_item->rowCount()== 1){
            $alerta= [
                "Alerta"=> "Limpiar",
                "Titulo"=> "ITEM Registrado",
                "Texto"=> "El Item se ha registrado con exito!",
                "Tipo"=> "success"
                ];
                echo json_encode($alerta);
        }else{
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "No hemos podido registrar el ITEM!!",
                "Tipo"=> "error"
                ];
                echo json_encode($alerta);

        }

    }/*Fin Controlador Agregar Item*/

    /*------ Controlador Eliminar Item ------*/
    public function eliminar_item_controlador(){
        /*Recibiendo ID del Item*/
        $id= $_POST['item_id_delet'];

        /*Comprobando el Item en la BD*/
        $check_item= mainModel::ejecutar_consulta_simple("SELECT item_id FROM item WHERE item_id='$id'");
        if($check_item->rowCount()<=0){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El ITEM que intentas eliminar NO EXISTE en el sistema",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        //Comprobando los Privilegios
        session_start(['name'=>'SPM']);
        if($_SESSION['privilegio_spm']!=1){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "No tienes los permisos necesarios para realizar esta accion",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();
        }

        $eliminar_item= itemModel::eliminar_item_model($id);

        if($eliminar_item->rowCount()==1){
            $alerta= [
                "Alerta"=> "Recargar",
                "Titulo"=> "ITEM Eliminado",
                "Texto"=> "El ITEM ha sido eliminado exitosamente!!",
                "Tipo"=> "success"
            ];

        }else{
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "No hemos podido eliminar el ITEM, por favor intente nuevamente!!",
                "Tipo"=> "error"
            ];
            
        }
        echo json_encode($alerta);//Convertimos a json el array "alerta"

    }//Fin Controlador Eliminar

    /*------ Controlador Paginar Item ------*/
    public function paginador_item_controlador($pagina,$registros,$url,$busqueda){
        $pagina= mainModel::limpiar_cadena($pagina);
        $registros= mainModel::limpiar_cadena($registros);

        $url= mainModel::limpiar_cadena($url);
        $url=SERVERURL.$url."/";

        $busqueda= mainModel::limpiar_cadena($busqueda);
        $tabla="";

        $pagina= (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
        $inicio= ($pagina>0) ? (($pagina*$registros)-$registros) : 0;

        if(isset($busqueda) && $busqueda!=""){
            $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM item WHERE 
            (item_codigo LIKE '%$busqueda%' OR item_nombre LIKE '%$busqueda%' OR 
            item_stock LIKE '%$busqueda%') ORDER BY item_nombre ASC LIMIT $inicio,$registros";
        }else{
            $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM item ORDER BY item_nombre ASC LIMIT $inicio,$registros";
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
                    <th>CODIGO</th>
                    <th>NOMBRE</th>
                    <th>STOCK</th>
                    <th>DETALLE</th>
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
                    <tr class="text-center">
                        <td>'.$contador.'</td>
                        <td>'.$rows['item_codigo'].'</td>
                        <td>'.$rows['item_nombre'].'</td>
                        <td>'.$rows['item_stock'].'</td>
                        <td>'.$rows['item_detalle'].'</td>
                        <td>
                            <a href="'.SERVERURL.'item-update/'.$rows['item_id'].'/" class="btn btn-success">
                                <i class="fas fa-sync-alt"></i>	
                            </a>
                        </td>
                        <td>
                            <form class="form-neon FormularioAjax" action="'.SERVERURL.'ajax/itemAjax.php" method="POST" data-form="delete" autocomplete="off">
                            <input type="hidden" name="item_id_delet" value="'.$rows['item_id'].'">
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
    }/*Fin Controlador paginador Item*/

    /*------ Controlador Datos Item ------*/
    public function datos_item_controlador($tipo,$id){
        
        $tipo=mainModel::limpiar_cadena($tipo);

        //$id=mainModel::decryption($id);
        $id=mainModel::limpiar_cadena($id);

        return itemModel::datos_item_model($tipo,$id);

    }/*Fin Controlador Datos ITEM*/


    /*------ Controlador Actualizar ITEM ------*/
    public function actualizar_item_controlador($datos){
        //Recibiendo el ID
        $id=$_POST['item_id_update'];

        $id=mainModel::limpiar_cadena($id);

        //Comprobar el Item en la BDD
        $check_item=mainModel::ejecutar_consulta_simple("SELECT * FROM item WHERE item_id='$id'");
        if($check_item->rowCount()<=0){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "NO pudimos encontrar el ITEM solicitado",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();
        }else{
        
        $campos= $check_item->fetch();

        }

        $codigo=mainModel::limpiar_cadena($_POST['item_codigo_up']);
        $nombre=mainModel::limpiar_cadena($_POST['item_nombre_up']);
        $stock=mainModel::limpiar_cadena($_POST['item_stock_up']);
        $estado=mainModel::limpiar_cadena($_POST['item_estado_up']);
        $detalle=mainModel::limpiar_cadena($_POST['item_detalle_up']);


        /*== Comprobar campos vacios ==*/

        if($codigo=="" || $nombre=="" || $stock=="" || $estado==""){
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

            if(mainModel::verificar_datos("[a-zA-Z0-9-]{1,45}",$codigo)){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El CODIGO no coincide con el formato solicitado",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

            }

            if(mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}",$nombre)){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El NOMBRE no coincide con el formato solicitado",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

            }

            if(mainModel::verificar_datos("[0-9]{1,9}",$stock)){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El campo STOCK no coincide con el formato solicitado",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

            }

            //Puede venir vacia la variable por no ser obligatorio, por eso la comprobacion antes de verificar
            if($detalle !=""){
                if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$detalle)){

                    $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El campo DETALLE no coincide con el formato solicitado",
                    "Tipo"=> "error"
                    ];
                    echo json_encode($alerta);//Convertimos a json el array "alerta"
                    exit();

            }
        }

        //Comprobando Privilegios
        session_start(['name'=>'SPM']);
        if($_SESSION['privilegio_spm']<1 || $_SESSION['privilegio_spm']>2){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "No tienes los permisos necesarios para realizar esta operacion",
                "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();
        }


        /*== Preparando datos para enviarlos al modelo ==*/

        $datos_item_update=[
            "Codigo"=>$codigo,
            "Nombre"=>$nombre,
            "Stock"=>$stock,
            "Estado"=>$estado,
            "Detalle"=>$detalle,
            "ID"=>$id
        ];

        if(itemModel::actualizar_item_model($datos_item_update)){

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

