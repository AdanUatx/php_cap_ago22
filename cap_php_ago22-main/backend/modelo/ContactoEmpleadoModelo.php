<?php

include_once "ModeloBase.php";

class ContactoEmpleadoModelo extends ModeloBase
{

    function __construct()
    {
        parent::__construct('contacto_empleado');
    }

    public function obtenerListado($condiciones = array())
    {
        $condionesSQL = $this->obtenerCondicionalesWhereAnd($condiciones);
        $consulta = "select * from contacto_empleado ce
                inner join catalogo_tipo_contacto ctc on ctc.id = ce.catalogo_tipo_contacto_id
                 $condionesSQL";
        return $this->obtenerResultadosQuery($consulta);
    }

}