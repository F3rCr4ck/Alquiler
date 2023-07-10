<?php

    if($peticionAjax){
        require_once "../config/server.php";//Si es una peticion ajax, estan en la carpeta ajax
    }else{
        require_once "./config/server.php";//Intentamos incluir desde index
    }


    class mainModel{
        /*------ Funcion para conectar BDD ------*/
        protected static function conectar(){

            //$conexion= new PDO('mysql:host=localhost;dbname=prueba', $usuario, $contraseña);
            $conexion= new PDO(SGDB,USER,PASS);
            $conexion->exec("SET CHARACTER SET utf8");
            return $conexion;
        }

        /*------ Funcion para ejecutar consultas simples ------*/
        protected static function ejecutar_consulta_simple($consulta){
            $sql= self::conectar()->prepare($consulta);//self es para llamarse a si mismo
            $sql->execute();
            return $sql;

        }

        /*------ Encriptar Cadenas ------*/
        public function encryption($string){
			$output=FALSE;
			$key=hash('sha256', SECRET_KEY);
			$iv=substr(hash('sha256', SECRET_IV), 0, 16);
			$output=openssl_encrypt($string, METHOD, $key, 0, $iv);
			$output=base64_encode($output);
			return $output;
		}

        /*------ Desencriptar Cadenas ------*/
		protected static function decryption($string){
			$key=hash('sha256', SECRET_KEY);
			$iv=substr(hash('sha256', SECRET_IV), 0, 16);
			$output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
			return $output;
		}

        /*------ Crear Codigos Aleatorios ------*/

        protected static function generar_codigo_aleatorio($letra,$longitud,$numero){

            for($i=1; $i<=$longitud; $i++){
                $aleatorio= rand(0,9);
                $letra.=$aleatorio; //concatena

            }

            return $letra.="-".$numero;

            //P345-1

        }

        /*------ Funcion limpiar cadenas ------*/
        /*------ Para Evitar inyecciones SQL ------*/
        protected static function limpiar_cadena($cadena){

            $cadena= trim($cadena);//Elimina los espacios de la cadena
            $cadena= stripcslashes($cadena);//Elimina Barras Invertidas
            $cadena= str_ireplace("<script>","", $cadena);//Busca la etiqueta y la elimina
            $cadena= str_ireplace("</script>","", $cadena);
            $cadena= str_ireplace("<script src","", $cadena);
            $cadena= str_ireplace("</script type=","", $cadena);
            /*------ Evita inyecciones SQL ------*/
            $cadena= str_ireplace("SELECT *FROM","", $cadena);
            $cadena= str_ireplace("DELETE FROM","", $cadena);
            $cadena= str_ireplace("INSERT INTO","", $cadena);
            $cadena= str_ireplace("DROP TABLE","", $cadena);
            $cadena= str_ireplace("DROP DATABASE","", $cadena);
            $cadena= str_ireplace("TRUNCATE TABLE","", $cadena);
            $cadena= str_ireplace("SHOW TABLES","", $cadena);
            $cadena= str_ireplace("SHOW DATABASES","", $cadena);
            $cadena= str_ireplace("<?php","", $cadena);
            $cadena= str_ireplace("?>","", $cadena);
            $cadena= str_ireplace("--","", $cadena);
            $cadena= str_ireplace(">","", $cadena);
            $cadena= str_ireplace("<","", $cadena);
            $cadena=str_ireplace("^", "", $cadena);
            $cadena= str_ireplace("[","", $cadena);
            $cadena= str_ireplace("]","", $cadena);
            $cadena= str_ireplace("==","", $cadena);
            $cadena= str_ireplace(";","", $cadena);
            $cadena= str_ireplace("::","", $cadena);


            $cadena= stripcslashes($cadena);//Elimina Barras Invertidas
            $cadena= trim($cadena);//Elimina los espacios de la cadena

            return $cadena;
            

        }

        /*------ Funcion para verificar Datos ------*/
        protected static function verificar_datos($filtro,$cadena){

            //Si coincide con el filtro(los simbolos que acepta)
            if(preg_match("/^".$filtro."$/", $cadena)){
                return false;

            }else{
                return true;//Si hay errores
            }
        }

        /*------ Funcion para verificar Fecha ------*/
        protected static function verificar_fechas($fecha){
            $valores= explode('-',$fecha);//Separa por guion 2023-5-4

            //checkdate(month,day,year)
            if(count($valores)== 3 && checkdate($valores[1],$valores[2],$valores[0])){
                return false;
            }else{
                return true; //Si hay errores
            }

        }

        /*------ Funcion de Paginador de tablas ------*/

        protected static function paginador_tablas($pag_act,$nroPag,$url,$botones){

            $tabla='<nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">';

            if($pag_act== 1){
                //Desabilita el boton en la primer pagina
                $tabla.='<li class="page-item disabled">
                <a class="page-link"><li class="fas fa-angle-double-left"></li></a>
                </li>';

            }else{
                $tabla.='<li class="page-item">
                <a class="page-link" href="'.$url.'1/"><li class="fas fa-angle-double-left"></li></a>
                </li>';

                $tabla.='<li class="page-item">
                <a class="page-link" href="'.$url.($pag_act-1).'/">Anterior</a></li>';
            }

            //$ci: contador de interaciones
            $ci=0;
            for($i=$pag_act;$i<=$nroPag;$i++){
                if($ci >= $botones){
                    break;
                }

                if($pag_act==$i){
                    $tabla.='<li class="page-item">
                    <a class="page-link active" href="'.$url.$i.'/">'.$i.'</a>
                    </li>';
                }else{
                    $tabla.='<li class="page-item">
                    <a class="page-link" href="'.$url.$i.'/">'.$i.'</a>
                    </li>';
                }

                $ci++;

            }

            if($pag_act== $nroPag){
                //Desabilita el boton en la ultima pagina
                $tabla.='<li class="page-item disabled">
                <a class="page-link"><li class="fas fa-angle-double-right"></li></a>
                </li>';

            }else{

                $tabla.='<li class="page-item">
                <a class="page-link" href="'.$url.($pag_act+1).'/">Siguiente</a></li>';

                $tabla.='<li class="page-item">
                <a class="page-link" href="'.$url.$nroPag.'/"><li class="fas fa-angle-double-right"></li></a>
                </li>';

            }
            $tabla.='</ul> </nav>';

            return $tabla;

        }

    }