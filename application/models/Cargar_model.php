<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Modelo del login de la aplicación
 * 
 * Este modelo realiza las consultas sobre los usuarios a la base de datos
 * y retorna los resultados al controlador.
 * 
 * @package AHA
 * @autor Juan Sebastián Rodríguez <rtjuansebastian@gmail.com>
 * @version 1.0
 * @copyright PoBox
 */
class Cargar_model extends CI_Model 
{
    
    /**
     * Constructor de la clase 
     * 
     * Metodo que carga los atributos de la clase CI_model
     */    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function cargar_csv($newcsvfile)
    {
        if (($gestor = fopen($newcsvfile, "r")) !== FALSE) 
        {
            $filas=0;
            $titulos=fgetcsv($gestor, 1000, ",");
            //$titulos=fgetcsv($gestor, 1000, ",");
            while (($datos=fgetcsv($gestor, 1000, ",","\""))!=FALSE) 
            {                
                $numero = count($datos);                            
                $registro=  $this->cargar_model->guardar_csv_array($numero,$titulos,$datos);
                $registros[]=$registro;
                $filas++;
            }
            fclose($gestor);
            return $registros;
        }
        else
        {
            return false;
        }
    }
    
    public function cargar_csv_schneider($newcsvfile)
    {
        if (($gestor = fopen($newcsvfile, "r")) !== FALSE) 
        {
            $filas=0;
            for($i=0;$i<=6;$i++){
               $titulos=fgetcsv($gestor, 1000, ","); 
            }
            $titulos=fgetcsv($gestor, 1000, ",");
            while (($datos=fgetcsv($gestor, 1000, ",","\""))!=FALSE) 
            {                
                $numero = count($datos);                            
                $registro=  $this->cargar_model->guardar_csv_array_schneider($numero,$titulos,$datos);
                if($registro){
                    $registros[]=$registro;                
                    $filas++;                    
                }
            }
            fclose($gestor);
            return $registros;
        }
        else
        {
            return false;
        }
    }    

    public function guardar_csv_array($numero,$titulos,$datos)
    {
        $registro=array();
        for ($c=0; $c < $numero; $c++) 
        {
                $registro[$titulos[$c]]=$datos[$c];
            }
        return $registro;
    }    
    
    public function guardar_csv_array_schneider($numero,$titulos,$datos)
    {
        $registro=array();
        for ($c=0; $c < $numero; $c++) 
        {
            if($datos[1]=="Total Subpartida" || $datos[1]=="Total General"){
                return FALSE;
            }
            if($c==4){
                $minimas=array();
                $descripciones=  explode(",", $datos[$c]);
                foreach ($descripciones as $descripcion){
                    $pos=strpos($descripcion, ":");
                    $descriptor=  substr($descripcion, 0,$pos);
                    $valor=  substr($descripcion,$pos+1);
                    $minimas[$descriptor]=$valor;
                }
                $registro[$titulos[$c]]=$minimas;
            }
            else{
                $registro[$titulos[$c]]=$datos[$c];
            }
        }
        return $registro;
    }     
        
}    