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
    
    public function schneider()
    {     
        $this->load->view("schneider");
    }

    public function exportar_excel_schneider()
    {
        $datos=array();
        $archivo_excel=array();
        if (null !==$this->input->post("convert")) 
        {            
            $filetoname = basename($_FILES['xlsxfile']['name']); 
            if($filetoname=="")
            {
                $datos['alert'] = "Seleccione un Archivo";    
            }
            else
            {
                $ext = substr($filetoname,-4);
                if($ext!="xlsx")
                {
                    $datos['alert'] = "Tipo de archivo invalido(".$ext.") . Seleccione un archivo '.xlsx'.";
                }
                else
                {
                    $result = @move_uploaded_file($_FILES['xlsxfile']['tmp_name'], $filetoname); // upload it 
                    $file=$filetoname;
                    $newcsvfile  = str_replace(".xlsx",".csv",$file);
                    $newcsvfile="/tmp/".$newcsvfile;
                    $objPHPExcel1 = new PHPExcel();
                    $objPHPExcel1->convertXLStoCSV($file,$newcsvfile);
                    $archivo_excel=$this->cargar_model->cargar_csv_schneider($newcsvfile);
                }
            }
        }
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("Schneider")->setDescription("Archivo para crear declaraciones de importación en Customsgm");
        $objPHPExcel->setActiveSheetIndex(0);
        //$objPHPExcel->getActiveSheet()->setTitle('Articulos');
        //poner los titulos de la hoja de calculo        

        //Estilos de la primer fila, fondo amarillo, tamaño de letra, tipo de letra.
        $styleArray = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff00')
            ),
            'font'  => array(
                'bold'  => true,
                'size'  => 14,
                'name'  => 'Calibri',
            )
        );               
        $objPHPExcel->getActiveSheet()->getStyle('A1:DB1')->applyFromArray($styleArray);        
        //Color de letra azul
        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => '0070c0')
            )
        );    
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);  
        //Color de letra morado
        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => '9900ff')
            )
        );         
        $objPHPExcel->getActiveSheet()->getStyle('B1:I1')->applyFromArray($styleArray);
        //Color de letra Verde
        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => '336633')
            )
        );         
        $objPHPExcel->getActiveSheet()->getStyle('J1:O1')->applyFromArray($styleArray);        
        //Color de letra Verde
        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => '0070c0')
            )
        );         
        $objPHPExcel->getActiveSheet()->getStyle('P1')->applyFromArray($styleArray);                
        //Color de letra Verde
        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => '0000ff')
            )
        );         
        $objPHPExcel->getActiveSheet()->getStyle('Q1:Y1')->applyFromArray($styleArray);                
        //Color de letra Verde
        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => '0070c0')
            )
        );         
        $objPHPExcel->getActiveSheet()->getStyle('Z1:DB1')->applyFromArray($styleArray);                        
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Producto');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Registro');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Proveedor');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'TipoReg');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'AñoRegis');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Programa');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Oficina');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'TipoImpo');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'FormaPago');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'Subpartida');
        $objPHPExcel->getActiveSheet()->setCellValue('K1', 'Unidad');
        $objPHPExcel->getActiveSheet()->setCellValue('L1', 'Pais');
        $objPHPExcel->getActiveSheet()->setCellValue('M1', 'Modalidad');
        $objPHPExcel->getActiveSheet()->setCellValue('N1', 'Acuerdo');
        $objPHPExcel->getActiveSheet()->setCellValue('O1', 'Adu de Paso');
        $objPHPExcel->getActiveSheet()->setCellValue('P1', 'Embalaje');
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Factura');
        $objPHPExcel->getActiveSheet()->setCellValue('R1', 'Fecha Factura');
        $objPHPExcel->getActiveSheet()->setCellValue('S1', 'Termino');
        $objPHPExcel->getActiveSheet()->setCellValue('T1', 'Lugar');
        $objPHPExcel->getActiveSheet()->setCellValue('U1', 'FormaEnvio');
        $objPHPExcel->getActiveSheet()->setCellValue('V1', 'Moneda');
        $objPHPExcel->getActiveSheet()->setCellValue('W1', 'Naturaleza');
        $objPHPExcel->getActiveSheet()->setCellValue('X1', 'Admon');
        $objPHPExcel->getActiveSheet()->setCellValue('Y1', 'FOBTotal');
        $objPHPExcel->getActiveSheet()->setCellValue('Z1', 'Unidad Dav');
        $objPHPExcel->getActiveSheet()->setCellValue('AA1', 'Cant Dav');
        $objPHPExcel->getActiveSheet()->setCellValue('AB1', 'Valor');
        $objPHPExcel->getActiveSheet()->setCellValue('AC1', 'Cant Dim');
        $objPHPExcel->getActiveSheet()->setCellValue('AD1', 'Peso Br');
        $objPHPExcel->getActiveSheet()->setCellValue('AE1', 'Peso Ne');
        $objPHPExcel->getActiveSheet()->setCellValue('AF1', 'Clasificacion');
        $objPHPExcel->getActiveSheet()->setCellValue('AG1', 'Texto_Alerta');
        $objPHPExcel->getActiveSheet()->setCellValue('AH1', 'Nombre Com(Dav)');
        $objPHPExcel->getActiveSheet()->setCellValue('AI1', 'Marca Com(Dav)');
        $objPHPExcel->getActiveSheet()->setCellValue('AJ1', 'Tipo(Dav)');
        $objPHPExcel->getActiveSheet()->setCellValue('AK1', 'Clase(Dav)');
        $objPHPExcel->getActiveSheet()->setCellValue('AL1', 'Modelo(Dav)');        
        $objPHPExcel->getActiveSheet()->setCellValue('AM1', 'Referencia(Dav)');
        $objPHPExcel->getActiveSheet()->setCellValue('AN1', 'Ano(Dav)');
        $objPHPExcel->getActiveSheet()->setCellValue('AO1', 'Otras Caracter(Dav)');
        $objPHPExcel->getActiveSheet()->setCellValue('AP1', 'Estado Mcia');
        $objPHPExcel->getActiveSheet()->setCellValue('AQ1', 'Orden Compra');
        $objPHPExcel->getActiveSheet()->setCellValue('AR1', 'Posicion');
        $objPHPExcel->getActiveSheet()->setCellValue('AS1', 'Descripcion_Inicial');
        $objPHPExcel->getActiveSheet()->setCellValue('AT1', 'Descripcion Complementaria');

        $i=2;                    
        foreach ($archivo_excel as $item)
        {
            $letra1=65;
            $letra2=85;//letra U(Ascii) donde empiezan las descripciones minimas
            $contador=0;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, "");
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, "X");
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, "Schneider");
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, "N");
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, "");
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, "");
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, "");
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, "");
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i, "");
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $item['Posición Arancelaria']);
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $item['Unidad Comercial']);
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $item['País de Origen']);
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $item['Modalidad Aduanera']);
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $item['Acuerdos']);
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$i, "");
            $objPHPExcel->getActiveSheet()->setCellValue('P'.$i, $item['embalaje']);
            $objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, $item['Factura']);
            $objPHPExcel->getActiveSheet()->setCellValue('R'.$i, "");
            $objPHPExcel->getActiveSheet()->setCellValue('S'.$i, "FOB");
            $objPHPExcel->getActiveSheet()->setCellValue('T'.$i, $item['País de compra']);
            $objPHPExcel->getActiveSheet()->setCellValue('U'.$i, "");
            $objPHPExcel->getActiveSheet()->setCellValue('V'.$i, "USD");
            $objPHPExcel->getActiveSheet()->setCellValue('W'.$i, "");
            $objPHPExcel->getActiveSheet()->setCellValue('X'.$i, "");
            $objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, "");
            $objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, $item['Unidad Comercial']);
            $objPHPExcel->getActiveSheet()->setCellValue('AA'.$i, $item['Cantidad Facturada']);
            $objPHPExcel->getActiveSheet()->setCellValue('AB'.$i, $item['Precio real']);
            $objPHPExcel->getActiveSheet()->setCellValue('AC'.$i, $item['Cantidad Facturada']);
            $objPHPExcel->getActiveSheet()->setCellValue('AD'.$i, $item['Peso Bruto']);
            $objPHPExcel->getActiveSheet()->setCellValue('AE'.$i, $item['Peso Neto']);
            $objPHPExcel->getActiveSheet()->setCellValue('AF'.$i, "");
            $objPHPExcel->getActiveSheet()->setCellValue('AG'.$i, "");
            /*$objPHPExcel->getActiveSheet()->setCellValue('AH'.$i, $item['producto']);
            $objPHPExcel->getActiveSheet()->setCellValue('AI'.$i, $item['producto']);
            $objPHPExcel->getActiveSheet()->setCellValue('AJ'.$i, $item['producto']);
            $objPHPExcel->getActiveSheet()->setCellValue('AK'.$i, $item['producto']);
            $objPHPExcel->getActiveSheet()->setCellValue('AL'.$i, $item['producto']);
            $objPHPExcel->getActiveSheet()->setCellValue('AM'.$i, $item['producto']);
            $objPHPExcel->getActiveSheet()->setCellValue('AN'.$i, $item['producto']);
            $objPHPExcel->getActiveSheet()->setCellValue('AO'.$i, $item['producto']);*/
            $objPHPExcel->getActiveSheet()->setCellValue('AP'.$i, "Nueva");
            $objPHPExcel->getActiveSheet()->setCellValue('AQ'.$i, $item['Orden de Compra']);
            $objPHPExcel->getActiveSheet()->setCellValue('AR'.$i, "");
            $objPHPExcel->getActiveSheet()->setCellValue('AS'.$i, "");
            $objPHPExcel->getActiveSheet()->setCellValue('AT'.$i, "");            

            foreach ($item['Descripción Mínima'] as $descriptor=>$valor)
            {
                $objPHPExcel->getActiveSheet()->setCellValue(chr($letra1).chr($letra2)."1", "DESCRIPTOR");
                $objPHPExcel->getActiveSheet()->setCellValue(chr($letra1).chr($letra2).$i, $descriptor);                
                $letra2++;
                $objPHPExcel->getActiveSheet()->setCellValue(chr($letra1).chr($letra2)."1", "D E S C R I P C I O N"); 
                $objPHPExcel->getActiveSheet()->setCellValue(chr($letra1).chr($letra2).$i, $valor); 
                $contador=$contador+2;
                if($contador>5)
                {
                    $letra1=66;
                }
                $letra2++;
                if($letra2>90)
                {
                    $letra2=65;
                }                                    
            }
            $i++;
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $uniqid = uniqid();
        $objWriter->save('./customsgm/' . $uniqid . '.xlsx');
        redirect(base_url('/customsgm/' . $uniqid . '.xlsx'));                                         
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