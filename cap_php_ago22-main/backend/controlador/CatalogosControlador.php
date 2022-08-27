<?php

include_once "modelo/CatalogoModelo.php";

class CatalogosControlador{

    private $catalogoModelo;

    function __construct()
    {
        $this->catalogoModelo = new CatalogoModelo();
    }


    public function obtenerCatalogoTipoContacto(){
        $catTipoContacto = $this->catalogoModelo->obtenerCatalogoTipoContacto();
        return array(
            'success' => true,
            'msg' => array('Se obtuvo el catalogo correctamente'),
            'data' => array(
                'catalogo_tipo_contacto' => $catTipoContacto
            )
        );
    }

}