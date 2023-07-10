<?php

    if($peticionAjax){
        require_once "../models/loginModel.php";
    }else{
        require_once "./models/loginModel.php";
    }


class loginController extends loginModel{

    /*------ Controlador Iniciar Sesion ------*/
    public function iniciar_sesion_controlador(){
        $usuario= mainModel::limpiar_cadena($_POST['usuario_log']);
        $clave= mainModel::limpiar_cadena($_POST['clave_log']);

        /*== Comprobar campos vacios==*/

        if($usuario=="" || $clave==""){
            echo '<script>
            Swal.fire({
                title: "Ocurrio un error Inesperado",
                text: "No ha Llenado todos los campos requeridos",
                type: "error",
                confirmButtonText: "Aceptar"
                
              });
            </script>';
            exit();

        }

        /*== Verificando integridad de los datos==*/
        if(mainModel::verificar_datos("[a-zA-Z0-9]{3,35}",$usuario)){

            echo '<script>
            Swal.fire({
                title: "Ocurrio un error Inesperado",
                text: "El NOMBRE DE USUARIO no coincide con el formato requerido",
                type: "error",
                confirmButtonText: "Aceptar"
                
              });
            </script>';
            exit();

        }

        /*== Verificando integridad de los datos==*/
        if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave)){

            echo '<script>
             Swal.fire({
                title: "Ocurrio un error Inesperado",
                text: "La CLAVE no coincide con el formato requerido",
                type: "error",
                confirmButtonText: "Aceptar"
                        
                });
            </script>';
            exit();
        
        }

        $clave= mainModel::encryption($clave);

        $datos_login=[
            "Usuario"=>$usuario,
            "Clave"=>$clave
        ];

        $datos_cuenta= loginModel::iniciar_sesion_model($datos_login);

        if($datos_cuenta->rowCount()==1){
            $row= $datos_cuenta->fetch();
            session_start(['name'=>'SPM']);
            $_SESSION['id_spm']= $row['usuario_id'];
            $_SESSION['nombre_spm']= $row['usuario_nombre'];
            $_SESSION['apellido_spm']= $row['usuario_apellido'];
            $_SESSION['usuario_spm']= $row['usuario_usuario'];
            $_SESSION['privilegio_spm']= $row['usuario_privilegio'];

            $_SESSION['token_spm']= md5(uniqid(mt_rand(),true));//Con esto evitamos que otro usuario nos cierre la sesion desde otra computadora

            return header("Location: ".SERVERURL."home/");
        }else{
            echo '<script>
            Swal.fire({
               title: "Ocurrio un error Inesperado",
               text: "El USUARIO o CLAVE son incorrectos",
               type: "error",
               confirmButtonText: "Aceptar"
                       
               }); 
           </script>';
           exit();
        }
        



    }//Fin Controlador de Iniciar Sesion

    /*------ Controlador Forzar Cierre de Sesion ------*/
    public function forzar_cierre_sesion_controlador(){

        session_unset();//vaciar sesion
        session_destroy();
        if(headers_sent()){

            return "<script> window.location.href='".SERVERURL."login/'</script>";

        }else{
            return header("Location: ".SERVERURL."login/");
        }

    }//Fin Controlador de Forzar Cierre de Sesion


    /*------ Controlador para Cierre de Sesion ------*/
    public function cierre_sesion_controlador(){
        session_start(['name'=>'SPM']);
        $token= mainModel::decryption($_POST['token']);
        $usuario= mainModel::decryption($_POST['usuario']);

        if($token == $_SESSION['token_spm'] && $usuario == $_SESSION['usuario_spm']){
            session_unset();
            session_destroy();
            $alerta= [
                "Alerta"=>"Redireccionar",
                "URL"=>SERVERURL."login/"

            ];

        }else{
            $alerta= [
                "Alerta"=> "Simple",
                "Titulo"=> "Error al Cerrar la Sesion",
                "Texto"=> "NO SE PUDO cerrar la Sesion en el sistema",
                "Tipo"=> "error"
            ];
    
        }
        echo json_encode($alerta);//Convertimos a json el array "alerta"
        //exit();

    }//Fin Controlador Cierre sesion




}