<?php

    if($peticionAjax){
        require_once "../models/prestamoModel.php";
    }else{
        require_once "./models/prestamoModel.php";
    }


class prestamoController extends prestamoModel{

    /*------ Controlador Buscar Cliente Prestamo ------*/
    public function buscar_cliente_prestamo_controlador(){
        /*Recuperar Texto*/
        $cliente=mainModel::limpiar_cadena($_POST['buscar_cliente']);

        /*Comprobar Texto*/
        if($cliente == ""){
            return '<div class="alert alert-warning" role="alert">
            <p class="text-center mb-0">
                <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                Debes introducir el DNI,Nombre,Apellido,Telefono
            </p>
        </div>';
        exit();  
        }
        /*Seleccionando Clientes en la BDD*/
        $datos_clientes=mainModel::ejecutar_consulta_simple("SELECT * FROM cliente WHERE cliente_dni LIKE '%$cliente%' OR cliente_nombre LIKE '%$cliente%' 
        OR cliente_apellido LIKE '%$cliente%' OR cliente_telefono LIKE '%$cliente%' ORDER BY cliente_nombre ASC");

        if($datos_clientes->rowCount()>=1){
            $datos_clientes= $datos_clientes->fetchAll();

            $tabla='<div class="table-responsive">
                        <table class="table table-hover table-bordered table-sm">
                    <tbody>';
                foreach($datos_clientes as $rows){
                    $tabla.='<tr class="text-center">
                    <td>'.$rows['cliente_nombre'].' '.$rows['cliente_apellido'].' - '.$rows['cliente_dni'].'</td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="agregar_cliente('.$rows['cliente_id'].')"><i class="fas fa-user-plus"></i></button>
                    </td>
                    </tr>';

                }
            $tabla.='</tbody></table></div>';

            return $tabla;
        }else{

            return '<div class="alert alert-warning" role="alert">
            <p class="text-center mb-0">
                <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                No hemos encontrado ningun cliente en el sistema que coincida con<strong>"'.$cliente.'"</strong>
            </p>
            </div>';
            
        }

    }/*Fin Controlador Buscar Cliente Prestamo*/

    /*------ Controlador Agregar Cliente Prestamo ------*/
    public function agregar_cliente_prestamo_controlador(){
        /*Recuperar ID del cliente*/
        $id=mainModel::limpiar_cadena($_POST['id_agregar_cliente']);

        /*Comprobando el cliente en la BDD*/
        $check_cliente=mainModel::ejecutar_consulta_simple("SELECT *FROM cliente WHERE cliente_id= '$id'");

        if($check_cliente->rowCount()<=0){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "NO hemos podido encontrar el cliente en la Base de Datos",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }else{

            $campos= $check_cliente->fetch();
        }

         /*Iniciando la Session*/
         session_start(['name'=>'SPM']);

         if(empty($_SESSION['datos_cliente'])){
            $_SESSION['datos_cliente']=[
                "ID"=>$campos['cliente_id'],
                "Dni"=>$campos['cliente_dni'],
                "Nombre"=>$campos['cliente_nombre'],
                "Apellido"=>$campos['cliente_apellido']
            ];

            $alerta= [
                "Alerta"=> "Recargar",
                "Titulo"=> "Cliente Agregado",
                "Texto"=> "El cliente se agrego para realizar un Prestamo o Reservacion",
                "Tipo"=> "success"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
         }else{
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "NO hemos podido agregar el cliente al Prestamo",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
         }


    }/*Fin Controlador Agregar Cliente Prestamo*/

    /*------ Controlador Eliminar Cliente Prestamo ------*/
    public function eliminar_cliente_prestamo_controlador(){

         /*Iniciando la Session*/
         session_start(['name'=>'SPM']);

         unset($_SESSION['datos_cliente']);

         if(empty($_SESSION['datos_cliente'])){
            $alerta= [
                "Alerta"=> "Recargar",
                "Titulo"=> "Cliente Removido",
                "Texto"=> "Los Datos del Cliente se han removido Correctamente",
                "Tipo"=> "success"
            ];
         }else{
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "NO hemos podido Eliminar los datos del Cliente",
                "Tipo"=> "error"
            ];

         }
         echo json_encode($alerta);//Convertimos a json el array "alerta"
         exit();
    }/*Fin Controlador Eliminar Cliente Prestamo*/

    /*------ Controlador Buscar Item Prestamo ------*/
    public function buscar_item_prestamo_controlador(){

        /*Recuperar Texto*/
        $item=mainModel::limpiar_cadena($_POST['buscar_item']);

        /*Comprobar Texto*/
        if($item == ""){
            return '<div class="alert alert-warning" role="alert">
            <p class="text-center mb-0">
                <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                Debes introducir el Codigo o el Nombre del Item
            </p>
        </div>';
        exit();  
        }
        /*Seleccionando Item en la BDD*/
        $datos_item=mainModel::ejecutar_consulta_simple("SELECT * FROM item WHERE (item_codigo LIKE '%$item%' OR item_nombre LIKE '%$item%') AND (item_estado='Habilitado') ORDER BY item_nombre ASC");

        if($datos_item->rowCount()>=1){
            $datos_item= $datos_item->fetchAll();

            $tabla='<div class="table-responsive">
                        <table class="table table-hover table-bordered table-sm">
                    <tbody>';
                foreach($datos_item as $rows){
                    $tabla.='<tr class="text-center">
                    <td>'.$rows['item_codigo'].'-'.$rows['item_nombre'].'</td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="modal_agregar_item('.$rows['item_id'].')"><i class="fas fa-box-open"></i></button>
                    </td>
                    </tr>';

                }
            $tabla.='</tbody></table></div>';

            return $tabla;
        }else{

            return '<div class="alert alert-warning" role="alert">
            <p class="text-center mb-0">
                <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                No hemos encontrado ningun ITEM en el sistema que coincida con<strong>"'.$item.'"</strong>
            </p>
            </div>';
            
        }
       


    }/*Fin Controlador Buscar Item Prestamo*/

    /*------ Controlador Agregar Item Prestamo ------*/
    public function agregar_item_prestamo_controlador(){
         /*Recuperar ID del Item*/
         $id=mainModel::limpiar_cadena($_POST['id_agregar_item']);

         /*Comprobando el Item en la BDD*/
         $check_item=mainModel::ejecutar_consulta_simple("SELECT *FROM item WHERE item_id= '$id' AND item_estado='Habilitado'");
 
         if($check_item->rowCount()<=0){
             $alerta= [
                 "Alerta"=> "Simple",
                 "Titulo"=> "Ocurrio un Error Inesperado",
                 "Texto"=> "NO hemos podido encontrar el Item en la Base de Datos",
                 "Tipo"=> "error"
             ];
             echo json_encode($alerta);//Convertimos a json el array "alerta"
             exit();
 
         }else{
 
             $campos= $check_item->fetch();//Creamos un array de datos
         }
 
          /*== Recuperando detalles del Prestamo ==*/
          $formato=mainModel::limpiar_cadena($_POST['detalle_formato']);
          $cantidad=mainModel::limpiar_cadena($_POST['detalle_cantidad']);
          $tiempo=mainModel::limpiar_cadena($_POST['detalle_tiempo']);
          $costo=mainModel::limpiar_cadena($_POST['detalle_costo_tiempo']);

          /*== Comprobar Campos Vacios ==*/
          if($cantidad=="" || $tiempo=="" || $costo==""){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "NO has llenado todos los campos que son obligatorios",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();
          }

          /*== Verificando integridad de los Datos==*/
            //pattern="" en la vista " caracteres{min,max}"

          if(mainModel::verificar_datos("[0-9]{1,7}",$cantidad)){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "La Cantidad no coincide con el formato solicitado",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

          }

          if(mainModel::verificar_datos("[0-9]{1,7}",$tiempo)){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El campo Tiempo no coincide con el formato solicitado",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

          }

          if(mainModel::verificar_datos("[0-9.]{1,15}",$costo)){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El campo Costo no coincide con el formato solicitado",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

          }

          if($formato!="Horas" && $formato!="Dias" && $formato!="Evento" &&$formato!="Mes"){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El Formato no es Valido",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();
          }

          session_start(['name'=>'SPM']);

         if(empty($_SESSION['datos_item'][$id])){
            $costo= number_format($costo,2,'.','');

            $_SESSION['datos_item'][$id]=[
                "ID"=>$campos['item_id'],
                "Codigo"=>$campos['item_codigo'],
                "Nombre"=>$campos['item_nombre'],
                "Detalle"=>$campos['item_detalle'],
                "Formato"=>$formato,
                "Cantidad"=>$cantidad,
                "Tiempo"=>$tiempo,
                "Costo"=>$costo
            ];

            $alerta= [
                "Alerta"=> "Recargar",
                "Titulo"=> "ITEM AGREGADO",
                "Texto"=> "El ITEM se agrego correctamente",
                "Tipo"=> "success"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            
         }else{
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El Item que intentas agregar ya se encuentra seleccionado",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
           
         }
        exit();
          

    }/*Fin Controlador Agregar Item Prestamo*/
    

    /*------ Controlador Eliminar item Prestamo ------*/
    public function eliminar_item_prestamo_controlador(){

        /*Recuperar ID del Item*/
        $id=mainModel::limpiar_cadena($_POST['id_eliminar_item']);
        /*Iniciando la Session*/
        session_start(['name'=>'SPM']);

        unset($_SESSION['datos_item'][$id]);

        if(empty($_SESSION['datos_item'][$id])){
            $alerta= [
                "Alerta"=> "Recargar",
                "Titulo"=> "Item Removido",
                "Texto"=> "El Item se han removido Correctamente",
                "Tipo"=> "success"
            ];
        }else{
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "NO hemos podido Eliminar el Item",
                "Tipo"=> "error"
            ];

        }
        echo json_encode($alerta);//Convertimos a json el array "alerta"
        exit();
    }/*Fin Controlador Eliminar item Prestamo */
    
    /*------ Controlador Datos Prestamo ------*/
    public function datos_prestamo_controlador($tipo,$id){
        $tipo= mainModel::limpiar_cadena($tipo);

        $id= mainModel::decryption($id);
        $id= mainModel::limpiar_cadena($id);

        return prestamoModel::datos_prestamo_model($tipo,$id);

    }/*Fin Controlador Datos Prestamo*/

    /*------ Controlador para Agregar Prestamo ------*/
    public function agregar_prestamo_controlador(){
        /*Iniciando Session */
        session_start(['name'=>'SPM']);

        /*------ Comprobando Items ------*/
        if($_SESSION['prestamo_item']== 0){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "NO has seleccionado ningun Item para realizar el Prestamo",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        /*------ Comprobando Clientes ------*/
        if(empty($_SESSION['datos_cliente'])){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "NO has seleccionado ningun Cliente para realizar el Prestamo",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();
        }

        /*== Recibiendo datos del Formulario ==*/
        $fecha_inicio=mainModel::limpiar_cadena($_POST['prestamo_fecha_inicio_reg']);
        $hora_inicio=mainModel::limpiar_cadena($_POST['prestamo_hora_inicio_reg']);
        $fecha_final=mainModel::limpiar_cadena($_POST['prestamo_fecha_final_reg']);
        $hora_final=mainModel::limpiar_cadena($_POST['prestamo_hora_final_reg']);
        $estado=mainModel::limpiar_cadena($_POST['prestamo_estado_reg']);
        $total_pagado=mainModel::limpiar_cadena($_POST['prestamo_pagado_reg']);
        $observacion=mainModel::limpiar_cadena($_POST['prestamo_observacion_reg']);

        /*== Comprobando Integridad de los datos ==*/
        //pattern="" en la vista " caracteres{min,max}"

        if(mainModel::verificar_fechas($fecha_inicio)){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "La Fecha de Inicio no coincide con el formato solicitado",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        if(mainModel::verificar_datos("([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])",$hora_inicio)){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "La Hora de Inicio no coincide con el formato solicitado",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        if(mainModel::verificar_fechas($fecha_final)){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "La Fecha de Entrega no coincide con el formato solicitado",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        if(mainModel::verificar_datos("([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])",$hora_final)){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "La Hora de Entrega no coincide con el formato solicitado",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }


        if(mainModel::verificar_datos("[0-9.]{1,10}",$total_pagado)){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El Total Depositado no coincide con el formato solicitado",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        if($observacion != ""){
            if(mainModel::verificar_datos("[a-zA-z0-9áéíóúÁÉÍÓÚñÑ#() ]{1,400}",$observacion)){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El campo observacion no coincide con el formato solicitado",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();
    
            }

        }

        
        /*== Comprobando Estados ==*/
        if($estado != "Reservacion" && $estado != "Prestamo" && $estado != "Finalizado"){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El Estado seleccionado no es Valido!!",
                "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

        }

        /*== Comprobando las Fechas ==*/

        if(strtotime($fecha_final)< strtotime($fecha_inicio)){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "La Fecha de entrega no puede ser menor que la Fecha de Inicio del Prestamo",
                "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

        }

        /*== Formateando Fechas Horas y Totales ==*/

        $total_prestamo= number_format($_SESSION['prestamo_total'],2,'.','');
        $total_pagado= number_format($total_pagado,2,'.','');

        $fecha_inicio= date("Y-m-d",strtotime($fecha_inicio));
        $fecha_final= date("Y-m-d",strtotime($fecha_final));

        $hora_inicio= date("h:i a",strtotime($hora_inicio));
        $hora_final= date("h:i a",strtotime($hora_final));

        /*== Generando Codigo de Prestamo ==*/
        $correlativo= mainModel::ejecutar_consulta_simple("SELECT prestamo_id FROM prestamo");
        $correlativo=($correlativo->rowCount())+1;

        //generar_codigo_aleatorio($letra,$longitud,$numero)
        //CP= Codigo de Prestamo
        $codigo=mainModel::generar_codigo_aleatorio("CP",7,$correlativo);

        $datos_prestamo_reg=[
            "Codigo"=>$codigo,
            "FechaInicio"=>$fecha_inicio,
            "HoraInicio"=>$hora_inicio,
            "FechaFinal"=>$fecha_final,
            "HoraFinal"=>$hora_final,
            "Cantidad"=>$_SESSION['prestamo_item'],
            "Total"=>$total_prestamo,
            "Pagado"=>$total_pagado,
            "Estado"=>$estado,
            "Observacion"=>$observacion,
            "Usuario"=>$_SESSION['id_spm'],
            "Cliente"=>$_SESSION['datos_cliente']['ID']


        ];

        /*== Agregar Prestamo ==*/
        $agregar_prestamo= prestamoModel::agregar_prestamo_model($datos_prestamo_reg);

        if($agregar_prestamo->rowCount() != 1){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado (Error: 001)",
                "Texto"=> "No Hemos podido registrar lo que es el Prestamo, Intente Nuevamente",
                "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

        }

        /*== Agregar Pago ==*/
        if($total_pagado > 0){

            $datos_pago_reg=[
                "Total"=>$total_pagado,
                "Fecha"=>$fecha_inicio,
                "CodPrestamo"=>$codigo
            ];

            $agregar_pago= prestamoModel::agregar_pago_model($datos_pago_reg);

            if($agregar_pago->rowCount()!= 1){
                pretamoModel::eliminar_prestamo_model($codigo,"Prestamo");
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado (Error: 002)",
                    "Texto"=> "No Hemos podido registrar lo que es el Prestamo, Intente Nuevamente",
                    "Tipo"=> "error"
                    ];
                    echo json_encode($alerta);//Convertimos a json el array "alerta"
                    exit();

            }
        }

        /*== Agregar Detalle del Prestamo ==*/
        $errores_detalle=0;

        foreach($_SESSION['datos_item'] as $items){
            $costo= number_format($items['Costo'],2,'.','');
            $descripcion= $items['Codigo']." ".$items['Nombre'];

            $datos_detalle_reg=[
                "Cantidad"=>$items['Cantidad'],
                "Formato"=>$items['Formato'],
                "Costo"=>$costo,
                "Tiempo"=>$items['Tiempo'],
                "Descripcion"=>$descripcion,
                "CodPrestamo"=>$codigo,
                "ItemID"=>$items['ID']
    
            ];

            $agregar_detalle= prestamoModel::agregar_detalle_model($datos_detalle_reg);
            if($agregar_detalle->rowCount()!= 1){
                $errores_detalle=1;
                break;
            
            }

        }

        if($errores_detalle== 0){
            unset($_SESSION['datos_cliente']);
            unset($_SESSION['datos_item']);
            $alerta= [
                "Alerta"=> "Recargar",
                "Titulo"=> "Prestamo Registrado!!",
                "Texto"=> "Los Datos del Prestamo han sido Registrados Exitosamente!!",
                "Tipo"=> "success"
            ];

        }else{
            prestamoModel::eliminar_prestamo_model($codigo,"Detalle");
            prestamoModel::eliminar_prestamo_model($codigo,"Pago");
            prestamoModel::eliminar_prestamo_model($codigo,"Prestamo");
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado (Error: 003)",
                "Texto"=> "No Hemos podido registrar lo que es el Prestamo, Intente Nuevamente",
                "Tipo"=> "error"
            ];
                
        }
        echo json_encode($alerta);//Convertimos a json el array "alerta"



    }/*Fin Controlador Agregar Prestamo*/

    /*------ Controlador para Agregar Pago ------*/
    public function agregar_pago_controlador(){

        /*--- Recibiendo Datos ---*/
        $codigo=mainModel::decryption($_POST['pago_codigo_reg']);
        $codigo=mainModel::limpiar_cadena($codigo);

        $monto=mainModel::limpiar_cadena($_POST['pago_monto_reg']);
        $monto= number_format($monto,2,'.','');

        /*--- Comprobando que el Pago sea mayor a Cero ---*/
        if($monto<=0){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El Pago debe ser Mayor a 0(CERO)",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        /*--- Comprobando prestamo en la BDD ---*/
        $datos_prestamo= mainModel::ejecutar_consulta_simple("SELECT *FROM prestamo WHERE prestamo_codigo='$codigo'");

        if($datos_prestamo->rowCount()<=0){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El Prestamo al que intenta agregar un pago no existe en el Sistema",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }else{
            $datos_prestamo=$datos_prestamo->fetch();
        }

        /*--- Comprobando que el monto no sea Mayor a lo que falta para el total ---*/
        $pendiente=number_format(($datos_prestamo['prestamo_total']-$datos_prestamo['prestamo_pagado']),2,'.','');

        if($monto > $pendiente){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El Monto que acaba de ingresar supera el saldo pendiente que tiene este prestamo",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

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

        //Calculando el total a pagar y la fecha
        $total_pagado=number_format(($monto+$datos_prestamo['prestamo_pagado']),2,'.','');
        $fecha=date("Y-m-d");

        $datos_pago_reg=[
            "Total"=>$monto,
            "Fecha"=>$fecha,
            "CodPrestamo"=>$codigo
        ];

        $agregar_pago= prestamoModel::agregar_pago_model($datos_pago_reg);
        if($agregar_pago->rowCount()== 1){
            $datos_prestamo_update=[
                "Tipo"=>"Pago",
                "Monto"=>$total_pagado,
                "Codigo"=>$codigo
            ];
            if(prestamoModel::actualizar_prestamo_model($datos_prestamo_update)){
                $alerta= [
                    "Alerta"=> "Recargar",
                    "Titulo"=> "Se ha Registrado con Exito el Pago",
                    "Texto"=> "El pago de ".MONEDA.$monto." se ha realizado con Exito",
                    "Tipo"=> "success"
                ];
            }else{
                prestamoModel::eliminar_prestamo_model($codigo,"Pago");
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "No hemos podido registrar el pago",
                    "Tipo"=> "error"
                ];
            }
        }else{
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "No hemos podido registrar el pago",
                "Tipo"=> "error"
            ];
        }


            echo json_encode($alerta);//Convertimos a json el array "alerta"









    }/*Fin Controlador para Agregar Pago*/

    /*------ Controlador Paginar Prestamos ------*/
    public function paginador_prestamo_controlador($pagina,$registros,$privilegio,$url,$tipo,$fecha_inicio,$fecha_final){
        $pagina= mainModel::limpiar_cadena($pagina);
        $registros= mainModel::limpiar_cadena($registros);
        $privilegio= mainModel::limpiar_cadena($privilegio);

        $url= mainModel::limpiar_cadena($url);
        $url=SERVERURL.$url."/";

        $tipo= mainModel::limpiar_cadena($tipo);
        $fecha_inicio= mainModel::limpiar_cadena($fecha_inicio);
        $fecha_final= mainModel::limpiar_cadena($fecha_final);

        $tabla="";

        $pagina= (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
        $inicio= ($pagina>0) ? (($pagina*$registros)-$registros) : 0;

        if($tipo=="Busqueda"){
            if(mainModel::verificar_fechas($fecha_inicio) || mainModel::verificar_fechas($fecha_final)){
                return '
                <div class="alert alert-danger text-center" role="alert">
					<p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
					<h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
					<p class="mb-0">Lo sentimos, no podemos realizar la busqueda ya que ha ingresado una Fecha Incorrecta.</p>
				</div>
                ';
                exit();
            }

        }

        //tabla.campo
        $campos="prestamo.prestamo_id,prestamo.prestamo_codigo,prestamo.prestamo_fecha_inicio,prestamo.prestamo_fecha_final,
        prestamo.prestamo_total,prestamo.prestamo_pagado,prestamo.prestamo_estado,prestamo.usuario_id,prestamo.cliente_id,cliente.cliente_nombre,cliente.cliente_apellido";

        if($tipo=="Busqueda" && $fecha_inicio!="" && $fecha_final!=""){
            $consulta="SELECT SQL_CALC_FOUND_ROWS $campos FROM prestamo INNER JOIN cliente ON prestamo.cliente_id=cliente.cliente_id WHERE 
            (prestamo.prestamo_fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_final') ORDER BY prestamo.prestamo_fecha_inicio DESC LIMIT $inicio,$registros";
        }else{
            $consulta="SELECT SQL_CALC_FOUND_ROWS $campos FROM prestamo INNER JOIN cliente ON prestamo.cliente_id=cliente.cliente_id WHERE prestamo.prestamo_estado= '$tipo'
            ORDER BY prestamo.prestamo_fecha_inicio DESC LIMIT $inicio,$registros";
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
                    <th>CLIENTE</th>
                    <th>FECHA DE PRÉSTAMO</th>
                    <th>FECHA DE ENTREGA</th>
                    <th>TIPO</th>
                    <th>ESTADO</th>
                    <th>FACTURA</th>';
                    if($privilegio== 1 || $privilegio== 2){
                        $tabla.='<th>ACTUALIZAR</th>';
                    }

                    if($privilegio== 1){
                        $tabla.='<th>ELIMINAR</th>';
                    }

        $tabla.='</tr>
            </thead>
            <tbody>';

         if($total>=1 && $pagina<=$Npaginas){
                $contador=$inicio+1;

                $reg_inicio= $inicio+1;

                foreach($datos as $rows){
                    $tabla.='
                    <tr class="text-center" >
                        <td>'.$contador.'</td>
                        <td>'.$rows['cliente_nombre'].' '.$rows['cliente_apellido'].'</td>
                        <td>'.date("d-m-Y",strtotime($rows['prestamo_fecha_inicio'])).'</td>
                        <td>'.date("d-m-Y",strtotime($rows['prestamo_fecha_final'])).'</td>
                        <td>'.$rows['prestamo_estado'].'</td>';

                        if($rows['prestamo_pagado']<$rows['prestamo_total']){
                            $tabla.='<td>Pendiente: <span class="badge badge-danger">'.MONEDA.number_format(($rows['prestamo_total']-$rows['prestamo_pagado']),2,'.',',').'</span></td>';
                        }else{
                            $tabla.='<td><span class="badge badge-light">Cancelado</span></td>';
                        }

                    $tabla.='
                    <td>
                        <a href="'.SERVERURL.'facturas/invoice.php?id='.mainModel::encryption($rows['prestamo_id']).'" class="btn btn-info" target="_blank">
                            <i class="fas fa-file-pdf"></i> 
                        </a>
                    </td>';

                        if($privilegio==1 || $privilegio== 2){
                            if($rows['prestamo_estado']=="Finalizado" && $rows['prestamo_pagado']==$rows['prestamo_total']){
                                $tabla.='
                                <td>
                                    <button class="btn btn-success" disabled>
                                    <i class="fas fa-sync-alt"></i>	
                                    </button>
                                </td>';
                            }else{
                                $tabla.='
                                <td>
                                    <a href="'.SERVERURL.'reservation-update/'.mainModel::encryption($rows['prestamo_id']).'/" class="btn btn-success">
                                    <i class="fas fa-sync-alt"></i>	
                                    </a>
                                </td>
                            ';
                            }
                        
                        }
                        if($privilegio==1){
                        $tabla.='<td>
                            <form class="form-neon FormularioAjax" action="'.SERVERURL.'ajax/prestamoAjax.php" method="POST" data-form="delete" autocomplete="off">
                            <input type="hidden" name="prestamo_codigo_delet" value="'.mainModel::encryption($rows['prestamo_codigo']).'">
                                <button type="submit" class="btn btn-warning">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>';
                        }

                $tabla.='</tr>';
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
                $tabla.='<p class="text-right">Mostrando Prestamos '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
            }
    return $tabla;
    }/*Fin Controlador paginador Prestamos*/

    /*------ Controlador Eliminar Prestamo ------*/
    public function eliminar_prestamo_controlador(){

        /*Recibiendo Codigo del Prestamo*/
        $codigo=mainModel::decryption($_POST['prestamo_codigo_delet']);
        $codigo=mainModel::limpiar_cadena($codigo);

        /*Comprobando Prestamo en la BDD*/

        $check_prestamo= mainModel::ejecutar_consulta_simple("SELECT prestamo_codigo FROM prestamo WHERE prestamo_codigo='$codigo'");

        if($check_prestamo->rowCount()<=0){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El Prestamo que intenta eliminar no existe en el Sistema",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }
        
        //Comprobar Privilegio
        session_start(['name'=>'SPM']);
        if($_SESSION['privilegio_spm'] != 1){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "No tienes los permisos necesarios para realizar esta accion",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();
        }

        /*-- Comprobando y Eliminar Pago del Prestamo --*/
        $check_pagos= mainModel::ejecutar_consulta_simple("SELECT prestamo_codigo FROM pago WHERE prestamo_codigo='$codigo'");
        $check_pagos=$check_pagos->rowCount();

        if($check_pagos>0){
            $eliminar_pagos= prestamoModel::eliminar_prestamo_model($codigo,"Pago");

            if($eliminar_pagos->rowCount()!=$check_pagos){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "No hemos podido eliminar el prestamo, Por Favor Intente Nuevamente",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();
            }
        }

        /*-- Comprobando y Eliminar Detalle del Prestamo --*/
        $check_detalle= mainModel::ejecutar_consulta_simple("SELECT prestamo_codigo FROM detalle WHERE prestamo_codigo='$codigo'");
        $check_detalle=$check_detalle->rowCount();

        if($check_detalle>0){
            $eliminar_detalle= prestamoModel::eliminar_prestamo_model($codigo,"Detalle");

            if($eliminar_detalle->rowCount()!=$check_detalle){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "No hemos podido eliminar los detalles del Prestamo, Por Favor Intente Nuevamente",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();
            }
        }

        $eliminar_prestamo= prestamoModel::eliminar_prestamo_model($codigo,"Prestamo");

        if($eliminar_prestamo->rowCount()==1){
            $alerta= [
                "Alerta"=> "Recargar",
                "Titulo"=> "Prestamo Eliminado!!",
                "Texto"=> "El Prestamo ha Sido eliminado del sistema",
                "Tipo"=> "success"
            ];

        }else{
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "No hemos podido eliminar el Prestamo, Por Favor Intente Nuevamente",
                "Tipo"=> "error"
            ];
        }

        echo json_encode($alerta);//Convertimos a json el array "alerta"

    }/*Fin Controlador Eliminar Prestamo */

    /*------ Controlador para Actualizar Prestamo ------*/
    public function actualizar_prestamo_controlador(){
        /*Recibiendo Codigo*/
        $codigo=mainModel::decryption($_POST['prestamo_codigo_up']);
        $codigo=mainModel::limpiar_cadena($codigo);

        /*--- Comprobando Prestamo en la BDD ---*/
        $check_prestamo= mainModel::ejecutar_consulta_simple("SELECT prestamo_codigo FROM prestamo WHERE prestamo_codigo='$codigo'");

        if($check_prestamo->rowCount()<=0){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El Prestamo que intenta Actualizar no existe en el Sistema",
                "Tipo"=> "error"
            ];
            echo json_encode($alerta);//Convertimos a json el array "alerta"
            exit();

        }

        /*--- Recibir Datos ---*/
        $estado= mainModel::limpiar_cadena($_POST['prestamo_estado_up']);
        $observacion= mainModel::limpiar_cadena($_POST['prestamo_observacion_up']);

        /*== Comprobando Integridad de los datos ==*/
        //pattern="" en la vista " caracteres{min,max}"

        if($observacion != ""){
            if(mainModel::verificar_datos("[a-zA-z0-9áéíóúÁÉÍÓÚñÑ#() ]{1,400}",$observacion)){
                $alerta= [
                    "Alerta"=> "Simple",
                    "Titulo"=> "Ocurrio un Error Inesperado",
                    "Texto"=> "El campo observacion no coincide con el formato solicitado",
                    "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();
    
            }

        }
      
        /*== Comprobando Estados ==*/
        if($estado != "Reservacion" && $estado != "Prestamo" && $estado != "Finalizado"){
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "El Estado seleccionado no es Valido!!",
                "Tipo"=> "error"
                ];
                echo json_encode($alerta);//Convertimos a json el array "alerta"
                exit();

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

        $datos_prestamo_update=[
            "Tipo"=>"Prestamo",
            "Estado"=>$estado,
            "Observacion"=>$observacion,
            "Codigo"=>$codigo

        ];

        if(prestamoModel::actualizar_prestamo_model($datos_prestamo_update)){
            $alerta= [
                "Alerta"=> "Recargar",
                "Titulo"=> "Prestamo Actualizado!!",
                "Texto"=> "Los datos del prestamo se han actualizados con Exito",
                "Tipo"=> "success"
                ];
        }else{
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Ocurrio un Error Inesperado",
                "Texto"=> "No hemos podido actualizar el prestamo",
                "Tipo"=> "error"
            ];
        }

        echo json_encode($alerta);//Convertimos a json el array "alerta"

    }/*Fin Controlador Actualizar Prestamo */
    
}