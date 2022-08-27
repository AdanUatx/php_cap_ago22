<?php

class ValidacionFormulario
{

    public static function validarFormEmpleadoNuevo($datosFormulario){
        $validacion['status'] = true;
        $validacion['msg'] = array();
        if(!isset($datosFormulario['nombre']) || $datosFormulario['nombre'] == ''){
            $validacion['status'] = false;
            $validacion['msg'][] = 'El campo nombre es requerido';
        }if(!isset($datosFormulario['paterno']) || $datosFormulario['paterno'] == ''){
            $validacion['status'] = false;
            $validacion['msg'][] = 'El campo apellido paterno es requerido';
        }if(!isset($datosFormulario['materno']) || $datosFormulario['materno'] == ''){
            $validacion['status'] = false;
            $validacion['msg'][] = 'El campo apellido materno es requerido';
        }if(!isset($datosFormulario['direccion']) || $datosFormulario['direccion'] == ''){
            $validacion['status'] = false;
            $validacion['msg'][] = 'El campo direccion es requerido';
        }if(!isset($datosFormulario['fecha_nacimiento']) || $datosFormulario['fecha_nacimiento'] == ''){
            $validacion['status'] = false;
            $validacion['msg'][] = 'El campo fecha de nacimiento es requerido';
        }
        return $validacion;
    }

    public static function validarFormEmpleadoActualizar($datosFormulario){
        $validacion = self::validarFormEmpleadoNuevo($datosFormulario);
        if(!isset($datosFormulario['id_empleado']) || $datosFormulario['id_empleado'] == ''){
            $validacion['status'] = false;
            $validacion['msg'][] = 'El campo identificador del empleado es requerido';
        }
        return $validacion;
    }

    public static function validarFormEmpleadoEliminar($datosFormulario){
        $validacion['status'] = true;
        $validacion['msg'] = array();
        if(!isset($datosFormulario['id']) || $datosFormulario['id'] == ''){
            $validacion['status'] = false;
            $validacion['msg'][] = 'El campo identificador del empleado es requerido';
        }
        return $validacion;
    }

}