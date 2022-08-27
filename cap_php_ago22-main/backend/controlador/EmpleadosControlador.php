<?php

include_once "modelo/EmpleadoModelo.php";
include_once "modelo/ContactoEmpleadoModelo.php";
include_once "helper/ValidacionFormulario.php";

class EmpleadosControlador
{

    private $empleadoModelo;
    private $contactoEmpleadoModelo;
    private $codigoRespuesta;

    function __construct()
    {
        $this->codigoRespuesta = 400;
        $this->empleadoModelo = new EmpleadoModelo();
        $this->contactoEmpleadoModelo = new ContactoEmpleadoModelo();
    }

    /*public function listado(){
        try{
        $empleadoDatos = $this->empleadoModelo->obtenerListado();
        $respuesta = array();
        foreach ($empleadoDatos as $index => $empleado){
            $condicionesWhere = array(
                'empleado_id' => $empleado['id']
            );
            $contactoEmpleado = $this->contactoEmpleadoModelo->obtenerListado($condicionesWhere);
            $empleado['datos_contacto'] = $contactoEmpleado;
            $empleadoRespuesta[$index] = $empleado;
        }
        //var_dump($empleadoDatos,$empleadoRespuesta);exit;
        //sleep(5);
        return array(
            'success' => true,
            'msg' => array('Se obtuvo el listado de empleados correctamente'),
            'data' => array(
                'empleados' => $respuesta
            )
        );
        $this->codigoRespuesta = 200;
        }catch(Exception $ex){
            return array(
                'success' => false,
                'msg' => array('Hubo un error al listar los datos'),
                'data' => array(
                    'empleados' => $respuesta
                )
            );
            $this->codigoRespuesta = 500;
        }
        return $respuesta;
    }*/

    public function listado(){
        $empleadoDatos = $this->empleadoModelo->obtenerListado();
        $empleadoRespuesta = array();
        foreach ($empleadoDatos as $index => $empleado){
            $condicionesWhere = array(
                'empleado_id' => $empleado['id']
            );
            $contactoEmpleado = $this->contactoEmpleadoModelo->obtenerListado($condicionesWhere);
            $empleado['datos_contacto'] = $contactoEmpleado;
            $empleadoRespuesta[$index] = $empleado;
        }
        //var_dump($empleadoDatos,$empleadoRespuesta);exit;
        //sleep(5);
        return array(
            'success' => true,
            'msg' => array('Se obtuvo el listado de empleados correctamente'),
            'data' => array(
                'empleados' => $empleadoRespuesta
            )
        );
    }

    /*public function agregar($datosFormulario){
        try{
            $datosContacto = $datosFormulario['listado_datos_contacto'];
            unset($datosFormulario['listado_datos_contacto']);
            $validacion = ValidacionFormulario::validarFormEmpleadoNuevo($datosFormulario);
            if($validacion['status']){
                $empleadoNuevo = $this->empleadoModelo->insertar($datosFormulario);
                if($empleadoNuevo){
                    //recuperar el id del empleado registrado
                    $idNuevoEmpleado = $this->empleadoModelo->ultimoID();
                    $guardoContacto = true;
                    foreach ($datosContacto as $dc){
                        $dc['empleado_id'] = $idNuevoEmpleado;
                        $guardoContacto = $this->contactoEmpleadoModelo->insertar($dc);
                    }if($guardoContacto){
                        $respuesta['success'] = true;
                        $respuesta['msg'];
                        $this->codigoRespuesta = 201;
                        
                    }else{
                        $respuesta['success'] = false;
                        $respuesta['msg'] = array('No fue posible guardar los datos' , 'Ocurrio un error en el sistema');
                        $this->codigoRespuesta = 500;
                    }
                }
            }else{
                $respuesta['success'] = false;
                $respuesta['msg'] = $validacion['msg'];
                $this->codigoRespuesta = 400;
            }
        }catch(Exception $ex){
            $respuesta['success'] = false;
            $respuesta['msg'] = array('Ocurrio un error en el servidor, favor de intentar mas tarde');
            $respuesta['msg'][] = $ex->getMessage();
            $this->codigoRespuesta = 500;
        }
       return $respuesta;
    }*/

    
     public function agregar($datosFormulario){
        $respuesta = array(
            'success' => false,
            'msg' => array('No fue posible agregar el empleado'),
        );
        //recuperar el listado de datos de contacto
        $datosContacto = $datosFormulario['listado_datos_contacto'];
        unset($datosFormulario['listado_datos_contacto']);
        $validacion = ValidacionFormulario::validarFormEmpleadoNuevo($datosFormulario);
        if($validacion['status']){
            $empleadoNuevo = $this->empleadoModelo->insertar($datosFormulario);
            if($empleadoNuevo){
                //recuperar el id del empleado registrado
                $idNuevoEmpleado = $this->empleadoModelo->ultimoID();
                $guardoContacto = true;
                foreach ($datosContacto as $dc){
                    $dc['empleado_id'] = $idNuevoEmpleado;
                    $guardoContacto = $this->contactoEmpleadoModelo->insertar($dc);
                }
                if($guardoContacto){
                    $respuesta = array(
                        'success' => true,
                        'msg' => array('Se registro el empleado correctamente'),
                        //devolver en el data, los datos del empleado agregado, incluido su id
                    );
                }else{
                    $respuesta = array(
                        'success' => false,
                        'msg' => array('Se registro el empleado correctamente pero faltaron los datos de contacto'),
                        //devolver en el data, los datos del empleado agregado, incluido su id
                    );
                }
            }
        }else{
            $respuesta['success'] = false;
            $respuesta['msg'] = $validacion['msg'];
        }
        return $respuesta;
    }

    public function actualizar($datosFormulario){
        $respuesta = array(
            'success' => false,
            'msg' => array('No fue posible actualizar el empleado'),
        );
        $datosContacto = $datosFormulario['listado_datos_contacto'];
        unset($datosFormulario['listado_datos_contacto']);
        $validacion = ValidacionFormulario::validarFormEmpleadoActualizar($datosFormulario);
        if($validacion['status']){
            $id_empleado = $datosFormulario['id_empleado'];
            unset($datosFormulario['id_empleado']);
            $empleadoActualizar = $this->empleadoModelo->actualizar($datosFormulario,array('id' => $id_empleado));
            if($empleadoActualizar){
                $guardoContacto = true;
                $this->contactoEmpleadoModelo->eliminar(array('empleado_id' => $id_empleado));
                foreach ($datosContacto as $dc){
                    $dc['empleado_id'] = $id_empleado;
                    $guardoContacto = $this->contactoEmpleadoModelo->insertar($dc);
                }
                if($guardoContacto){
                    $respuesta = array(
                        'success' => true,
                        'msg' => array('Se actualizo el empleado correctamente'),
                        //devolver en el data, los datos del empleado agregado, incluido su id
                    );
                }else{
                    $respuesta = array(
                        'success' => false,
                        'msg' => array('Se el empleado correctamente pero faltaron los datos de contacto'),
                        //devolver en el data, los datos del empleado agregado, incluido su id
                    );
                }
            }else{
                $respuesta['success'] = false;
                $respuesta['msg'] = $this->empleadoModelo->getErrores();
            }
        }else{
            $respuesta['success'] = false;
            $respuesta['msg'] = $validacion['msg'];
        }
        return $respuesta;
    }


    //Funcion de Eliminar los datos
    public function eliminar($datosFormulario){
        try{
            $validacion = ValidacionFormulario::validarFormEmpleadoEliminar($datosFormulario);
            if($validacion['status']){
                $empleadoEliminar = $this->empleadoModelo->eliminar(array('id' => $datosFormulario['id']));
            }if($empleadoEliminar){
                   $respuesta['success'] = true;
                   $respuesta['msg'] = array ('Se elimino correctamente');
                   $this->codigoRespuesta = 200;
            }else{
                    $respuesta['success'] = false;
                    $respuesta['msg'] = $this->empleadoModelo->getErrores();
                    $this->codigoRespuesta = 400;
            }
        }catch (Exception $ex){
            $respuesta['success'] = false;
            $respuesta['msg'] = $validacion['msg'];
            $this->codigoRespuesta = 500;
        }
           return $respuesta;
    }
    

    /*public function eliminar($datosFormulario){
        $respuesta = array(
            'success' => false,
            'msg' => array('No fue posible actualizar el empleado'),
        );
        $validacion = ValidacionFormulario::validarFormEmpleadoEliminar($datosFormulario);
        if($validacion['status']){
            $empleadoEliminar = $this->empleadoModelo->eliminar(array('id' => $datosFormulario['id']));
            if($empleadoEliminar){
                $respuesta = array(
                    'success' => true,
                    'msg' => array('Se eliminó el empleado correctamente'),
                );
            }else{
                $respuesta['success'] = false;
                $respuesta['msg'] = $this->empleadoModelo->getErrores();
            }
            }else{
            $respuesta['success'] = false;
            $respuesta['msg'] = $validacion['msg'];
            }
        return $respuesta;
    }*/

    public function getCodigoRespuesta(){
        return $this->codigoRespuesta;
    }
}