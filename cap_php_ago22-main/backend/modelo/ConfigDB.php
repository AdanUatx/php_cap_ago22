<?php

class ConfigDB
{

    public static function getConfig(){
        switch ($_SERVER['SERVER_NAME']){
            /*case 'ecoronar.000webhostapp.com':
                $dbConfig = array(
                    'hostname' => 'localhost',
                    'usuario' => 'id18354401_cap_php_softura',
                    'password' => 'Pa$$word1234',
                    'base_datos' => 'id18354401_ecoronar',
                    'puerto'=>'3306',
                    'set_charset'=> 'utf-8'
                );
                break;*/
            default :
                $dbConfig = array(
                    'hostname' => 'localhost',
                    'usuario' => 'root',
                    'password' => '',
                    'base_datos' => 'cap_softura_php',
                    'puerto'=>'3306',
                    'set_charset'=> 'uft-8'
                );
                break;
        }
        return $dbConfig;
    }

}