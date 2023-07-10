<?php

const SERVER="localhost";
const DB="prestamos";//Nombre de la Base de Datos
const USER="root";//Por defecto es root 
const PASS="";

const SGDB= "mysql:host=".SERVER.";dbname=".DB;

//Configuracion para encriptar las contraseñas y otros parametros
const METHOD= "AES-256-CBC";//Metodo para encriptar
const SECRET_KEY='prestamos@2023';
const SECRET_IV='037970';