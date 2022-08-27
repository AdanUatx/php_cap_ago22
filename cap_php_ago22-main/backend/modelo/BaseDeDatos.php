<?php

include_once "ConfigDB.php";

class BaseDeDatos{

    private $dbConfig;
    private $mysqli;
    private $errores;

    function __construct()
    {
        try{
            $this->dbConfig = ConfigDB::getConfig();
            $this->mysqli = new mysqli(
                $this->dbConfig['hostname'],
                $this->dbConfig['usuario'],
                $this->dbConfig['password'],
                $this->dbConfig['base_datos'],
                $this->dbConfig['puerto']
            );
            if($this->mysqli->connect_errno){
                $this->errores = $this->mysqli->error_list;
                echo 'Error en la conexion base de datos';die;
            }else{
                $this->errores = array();
            }
        }catch (Exception $ex){
            $this->errores = $this->mysqli->error_list;
            echo 'Error en la conexion de BD';die;
        }
    }

    public function consultaRegistros($tabla,$condicionales = array()){
        $condiciones = $this->obtenerCondicionalesWhereAnd($condicionales);
        $query = $this->mysqli->query("select * from ".$tabla.$condiciones);
        $datos = array();
        while($registro = $query->fetch_assoc()){
            $datos[] = $registro;
        }
        return $datos;
    }

    public function obtenerResultadosQuery($stringSQL){
        $query = $this->mysqli->query($stringSQL);
        $datos = array();
        while($registro = $query->fetch_assoc()){
            $datos[] = $registro;
        }
        return $datos;
    }

    /**
     * @param $tabla
     * @param $valoresInsert
     * los valores insert es un array con los datos
     * array('nombre_columna1' => valor, 'nombre_columna' => valor)
     */
    public function insertarRegistro($tabla,$valoresInsert){
        $columnasValoresSQL = $this->obtengaClavesYvaloresInsert($valoresInsert);
        $consultaInsertSQL = "INSERT INTO ".$tabla."(".$columnasValoresSQL['columnas'].") VALUES (".$columnasValoresSQL['valores'].")";
        try{
            $query = $this->mysqli->query($consultaInsertSQL);
            if($query !== true){
                return false;
            }return true;
        }catch (Exception $ex){
            return false;
        }
    }

    public function actualizarRegistro($tabla,$valoresUpdate,$condicionales){
        try{
            $sqlCamposUpdate = $this->obtenerColumnaValorUpdate($valoresUpdate);
            $condicionesSQL = $this->obtenerCondicionalesWhereAnd($condicionales);
            $consultaUpdateSQL = "UPDATE $tabla SET $sqlCamposUpdate $condicionesSQL";
            $query = $this->mysqli->query($consultaUpdateSQL);
            if($query !== true){
                $this->errores = $this->mysqli->error_list;
                return false;
            }return true;
        }catch (Exception $ex){
            return false;
        }
    }

    public function eliminarRegistro($tabla,$condicionales){
        try{
            $condicionesSQL = $this->obtenerCondicionalesWhereAnd($condicionales);
            $consultaDeleteSQL = "DELETE FROM $tabla $condicionesSQL";
            $query = $this->mysqli->query($consultaDeleteSQL);
            if($query !== true){
                $this->errores = $this->mysqli->error_list;
                return false;
            }return true;
        }catch (Exception $ex){
            return false;
        }
    }

    public function ultimoID(){                 /*       
        return $this->mysqli->insert_id;            Recuperar el ultimo ID registrado */
    }                                           

    public function getErrores(){
        $msgError = array();
        foreach ($this->errores as $e){
            $msgError[] = "Codigo error: ".$e['errno']." Detalle/Explicacion: ".$e['error'];
        }
        return $msgError;
    }

    /**  functiones privadas
     * */

    /**
     * @param $condicionales
     * @return string
     * funcion que recibe un arragle de condiciones para los SQL where
     * array(array('nombre_columna1'=> valor1), array('nombre_columna2'=> valor2),...)
     */
    public function obtenerCondicionalesWhereAnd($condicionales){
        $condiciones = " WHERE 1=1";
        foreach ($condicionales as $columna => $valor){
            $condiciones .= " AND $columna = '$valor'";
        }
        return $condiciones;
    }

    /**
     * @param $valoresInsert
     * array(array('nombre_columna1'=> valor1), array('nombre_columna2'=> valor2),...)
     */
    private function obtengaClavesYvaloresInsert($valoresInsert){
        $retorno = array();
        $nombresColumnas = "";//nombre,paterno,materno,....
        $valoresColumnas = "";//'enrique','corona','ricaño',...
        $iteracionCampos = 1;
        $maxItCampo = sizeof($valoresInsert);
        foreach ($valoresInsert as $columna => $valor){
            $nombresColumnas .= $columna;
            $valoresColumnas .= "'".$valor."'";
            if($iteracionCampos < $maxItCampo){
                $nombresColumnas .= ',';
                $valoresColumnas .= ',';
            }
            $iteracionCampos++;
        }
        $retorno['columnas'] = $nombresColumnas;
        $retorno['valores'] = $valoresColumnas;
        return $retorno;
    }

    /**
     * array(array('nombre_columna1'=> valor1), array('nombre_columna2'=> valor2),...)
     */
    private function obtenerColumnaValorUpdate($camposUpdate){
        $camposValorSQL = '';
        $iteracionCampos = 1;
        $maxItCampo = sizeof($camposUpdate);
        foreach ($camposUpdate as $columna => $valor){
            $camposValorSQL .= " $columna = '$valor'";
            if($iteracionCampos < $maxItCampo){
                $camposValorSQL .= ',';
            }
            $iteracionCampos++;
        }
        return $camposValorSQL;
    }

}