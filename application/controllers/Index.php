<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador de index la aplicación
 * 
 * Este controlador conecta las funciones del modelo y las 
 * vistas del index.
 * 
 * @package AHA
 * @autor Juan Sebastián Rodríguez <rtjuansebastian@gmail.com>
 * @version 1.0
 * @copyright PoBox
 */

class Index extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('PHPExcel/Classes/PHPExcel');
        $this->load->model('cargar_model');
    }
    
    /**
     * Metodo index
     * 
     * Este metodo es el encargado de cargar la pagina de inicio.
     */     
    public function index()
    {
        $this->load->view('prueba');
    }
    
    public function exportar_excel()
    {
        $productos=array();
        $archivo_excel=array();
        if (null !==$this->input->post("convert")) 
        {            
            $filetoname = basename($_FILES['xlsxfile']['name']); 
            if($filetoname=="")
            {
                $alert = "Seleccione un Archivo";    
            }
            else
            {
                $ext = substr($filetoname,-4);
                if($ext!="xlsx")
                {
                    $alert = "Tipo de archivo invalido. Seleccione un archivo '.xlsx'.";
                }
                else
                {
                    $result = @move_uploaded_file($_FILES['xlsxfile']['tmp_name'], $filetoname); // upload it 
                    $file=$filetoname;
                    $newcsvfile  = str_replace(".xlsx",".csv",$file);
                    //$newcsvfile="C:/Users/Juan/Documents/".$newcsvfile;
                    $newcsvfile="/tmp/".$newcsvfile;
                    $objPHPExcel1 = new PHPExcel();
                    $objPHPExcel1->convertXLStoCSV($file,$newcsvfile);
                    $archivo_excel=$this->cargar_model->cargar_csv($newcsvfile);
                }
            }
        }
        foreach ($archivo_excel as $registro)
        {
            $productos[$registro['PROCOD,C,40']]['codigo']=$registro['PROCOD,C,40'];
            $productos[$registro['PROCOD,C,40']]['subpartida']=$registro['ARAPOS,C,10'];
            $texto=$registro['PRODES,M'];
            $lineas = explode("\n", $texto);
            $descripciones=array();
            foreach ($lineas as $linea)
            {
                $pos = strpos($linea, ":");
                if($pos)
                {
                    $descriptor[0]= substr($linea, 0, $pos);                
                    $descriptor[1]= substr($linea, $pos+1);
                }
                else 
                {
                    $descriptor[0]=$linea;
                }
                //$descriptor=  explode(":", $linea);
                if(isset($descriptor[1]))
                {
                    $descripciones[$descriptor[0]]=$descriptor[1];
                }
                else
                {
                    $descripciones[$descriptor[0]]="";
                }
                unset($descriptor);
            }
            $productos[$registro['PROCOD,C,40']]['descripciones']=$descripciones;
        }

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("productos")->setDescription("Archivo para crear productos en Customsgm");
        $objPHPExcel->setActiveSheetIndex(0);        
        $styleArray = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 14,
                'name'  => 'Calibri',
            )
        );               
        $objPHPExcel->getActiveSheet()->getStyle('A1:DB1')->applyFromArray($styleArray); 
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'codigo');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'importador');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'subpartida');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Clasificacion');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'TEXTO ALERTA');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'NOMBRE');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'MARCA');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'TIPO');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'CLASE');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'MODELO');
        $objPHPExcel->getActiveSheet()->setCellValue('K1', 'REFERENCIA');
        $objPHPExcel->getActiveSheet()->setCellValue('L1', 'OTRAS CARACT.');
        $objPHPExcel->getActiveSheet()->setCellValue('M1', 'DESCRIPCIONINICIAL');
        $objPHPExcel->getActiveSheet()->setCellValue('N1', 'DESCRIPCION FINAL');
        $i=2;
        foreach ($productos as $producto)
        {
            $letra=79;
            $letra2=65;
            $contador=1;
            if(isset($producto['codigo'])){$objPHPExcel->getActiveSheet()->setCellValue('A'.$i.'', $producto['codigo']);}
            if(isset($producto['subpartida'])){$objPHPExcel->getActiveSheet()->setCellValue('C'.$i.'', $producto['subpartida']);}
            if(isset($producto['descripciones']['REF'])){$objPHPExcel->getActiveSheet()->setCellValue('K'.$i.'', $producto['descripciones']['REF']);}
            if(isset($producto['descripciones']['NOMBRE COMERCIAL'])){$objPHPExcel->getActiveSheet()->setCellValue('F'.$i.'', $producto['descripciones']['NOMBRE COMERCIAL']);}
            foreach ($producto['descripciones'] as $descriptor => $valor)
            {
                if($contador>12)
                {
                    $objPHPExcel->getActiveSheet()->setCellValue("A".chr($letra2)."1", "ID");
                    $objPHPExcel->getActiveSheet()->setCellValue("A".chr($letra2).$i, $descriptor);
                    $letra2++;
                    $objPHPExcel->getActiveSheet()->setCellValue("A".chr($letra2).$i, $valor);
                    $letra2++;    
                    if($letra2>90)
                    {
                        $letra2=65;
                    }  
                }
                else 
                {
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($letra)."1", "ID");
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($letra).$i, $descriptor);
                    $letra++;
                    $objPHPExcel->getActiveSheet()->setCellValue(chr($letra).$i, $valor);
                    $letra++;
                }                
                $contador=$contador+2;                 
            }
            $i++;
        }
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $uniqid = uniqid();
        $objWriter->save('./assets/' . $uniqid . '.xlsx');
        redirect(base_url('/assets/' . $uniqid . '.xlsx'));                
    }
}