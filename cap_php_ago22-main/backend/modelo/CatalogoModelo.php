<?php

include_once "CatalogoTipoContacto.php";

class CatalogoModelo
{

    public function obtenerCatalogoTipoContacto(){
        try{
            $ctcModel = new CatalogoTipoContacto();
            $catTipoContacto = $ctcModel->obtenerListado();
            return $catTipoContacto;
        }catch (Exception $ex){
            echo 'Error en la conexion de BD';die;
        }
    }

}