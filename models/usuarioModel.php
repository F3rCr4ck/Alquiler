<?php

    require_once "mainModel.php";

    class usuarioModel extends mainModel{
        /*------ Modelo Agregar Usuario ------*/
        protected static function agregar_usuario_model($datos){
            $sql= mainModel::conectar()->prepare("INSERT INTO usuario(usuario_dni,usuario_nombre,usuario_apellido,usuario_telefono,
            usuario_direccion,usuario_email,usuario_usuario,usuario_clave,usuario_estado,usuario_privilegio) 
            VALUES(:DNI,:Nombre,:Apellido,:Telefono,
            :Direccion,:Email,:Usuario,:Clave,:Estado,:Privilegio)");
            
            //bindParam(":Marcador",$datos['Indice']) 
            $sql->bindParam(":DNI",$datos['DNI']);//Para poder Sustituir los marcadores
            $sql->bindParam(":Nombre",$datos['Nombre']);
            $sql->bindParam(":Apellido",$datos['Apellido']);
            $sql->bindParam(":Telefono",$datos['Telefono']);
            $sql->bindParam(":Direccion",$datos['Direccion']);
            $sql->bindParam(":Email",$datos['Email']);
            $sql->bindParam(":Usuario",$datos['Usuario']);
            $sql->bindParam(":Clave",$datos['Clave']);
            $sql->bindParam(":Estado",$datos['Estado']);
            $sql->bindParam(":Privilegio",$datos['Privilegio']);

            $sql->execute();

            return $sql;
            
        }//Fin de agregar_usuario_model

        /*------ Modelo Eliminar Usuario ------*/

        protected static function eliminar_usuario_model($id){
            $sql=mainModel::conectar()->prepare("DELETE FROM usuario WHERE usuario_id=:ID");
            $sql->bindParam(":ID",$id);

            $sql->execute();

            return $sql;

        }

        /*------ Modelo Datos Usuario ------*/

        protected static function datos_usuario_model($tipo,$id){

            if($tipo=="Unico"){
                $sql=mainModel::conectar()->prepare("SELECT * FROM usuario WHERE usuario_id=:ID");
                $sql->bindParam(":ID",$id);
            }else if($tipo=="Conteo"){
                $sql=mainModel::conectar()->prepare("SELECT usuario_id FROM usuario WHERE usuario_id!='1'");

            }

            $sql->execute();

            return $sql;

        }

         /*------ Modelo Actualizar Usuario ------*/

         protected static function actualizar_usuario_model($datos){
            $sql=mainModel::conectar()->prepare("UPDATE usuario SET usuario_dni=:DNI, usuario_nombre=:Nombre, usuario_apellido=:Apellido,
                                                usuario_telefono=:Telefono,usuario_direccion=:Direccion,usuario_email=:Email,
                                                usuario_usuario=:Usuario,usuario_clave=:Clave,usuario_estado=:Estado,usuario_privilegio=:Privilegio WHERE usuario_id=:ID");

            $sql->bindParam(":DNI",$datos['DNI']);//Para poder Sustituir los marcadores
            $sql->bindParam(":Nombre",$datos['Nombre']);
            $sql->bindParam(":Apellido",$datos['Apellido']);
            $sql->bindParam(":Telefono",$datos['Telefono']);
            $sql->bindParam(":Direccion",$datos['Direccion']);
            $sql->bindParam(":Email",$datos['Email']);
            $sql->bindParam(":Usuario",$datos['Usuario']);
            $sql->bindParam(":Clave",$datos['Clave']);
            $sql->bindParam(":Estado",$datos['Estado']);
            $sql->bindParam(":Privilegio",$datos['Privilegio']);
            $sql->bindParam(":ID",$datos['ID']);

            $sql->execute();

            return $sql;
         }


    }