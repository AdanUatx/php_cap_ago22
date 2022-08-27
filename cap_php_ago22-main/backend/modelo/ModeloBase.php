<?php

/**
 * realizar las operaciones basicas de la base de datos
 * CRUD: select, update, delete, insert
 * dinamicamente es el nombre de la tabla
 */

include_once "BaseDeDatos.php";

class ModeloBase extends BaseDeDatos
{

    private $tabla;

    function __construct($nombreTabla)
    {
        parent::__construct();
        $this->tabla = $nombreTabla;
    }

    public function obtenerListado($condiciones = array()){
        return $this->consultaRegistros($this->tabla,$condiciones);
    }

    public function insertar($valoresInsert){
        return $this->insertarRegistro($this->tabla,$valoresInsert);
    }

    public function actualizar($valoresUpdate,$condicionales){
        return $this->actualizarRegistro($this->tabla,$valoresUpdate,$condicionales);
    }

    public function eliminar($condiciones){
        return $this->eliminarRegistro($this->tabla,$condiciones);
    }

}