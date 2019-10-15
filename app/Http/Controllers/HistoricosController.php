<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\d_timbrado;
use Illuminate\Support\Facades\DB;


class HistoricosController extends Controller
{     
     
    public function index(){
        $iu_usuario  = session()->get('no_usuario');  
        return d_timbrado::where('iu_usuario', $iu_usuario)->get();        
    }
     
    //Función para obtener el total de timbres que generó el cliente desde que empezó a timbrar con nosotros
    public function totales(){ 

             $timbrado_total = 0;
             $iu_usuario  = session()->get('no_usuario');
             $fecha_ini = date("Y-m-d").": 00:00:00";
             $fecha_fin = date("Y-m-d").": 23:59:59";
            
            $historico = DB::connection('historico_reporteador')->select('call spTotalHistorico("'.$iu_usuario.'")');       
            $total = json_decode( json_encode($historico), true);
            foreach ($total as $key ) {
              
               $totales = number_format(implode($key));                                        
            } 
            return $totales;



            
    }

    public function t_ayer(){
        $ayer_t = 0;
        $ayer_r = 0;
        $iu_usuario  = session()->get('no_usuario');
        $ayer_t = DB::select('call spDiaAnterior("'.$iu_usuario.'")');   
        $data_t= json_decode( json_encode($ayer_t), true);
        foreach ($data_t as $key ) {
           $t_ayer = $key;
           $resultado_t = implode($t_ayer);                     
        }
        $ayer_r = DB::connection('retenciones_envio')->select('call spDiaAnteriorRet("'.$iu_usuario.'")');   
        $data_r= json_decode( json_encode($ayer_r), true);
        foreach ($data_r as $key ) {
           $t_ayer = $key;
           $resultado_r = implode($t_ayer);                                        
        }        
        $suma =  $resultado_r+$resultado_t;        
        $suma = number_format($suma);                
        return $suma;
    }

    public function t_hoy(){
        $hoy_t  = 0;
        $hoy_r = 0;
        $iu_usuario  = session()->get('no_usuario');
        $fecha_ini = date("Y-m-d").": 00:00:00";
        $fecha_fin = date("Y-m-d").": 23:59:59";
        
        $hoy_t = DB::connection('tr_core_timbrado')->select("SELECT count(*) as timbrado_hoy FROM d_timbrado where iu_usuario = ".$iu_usuario." and fechah_timbrado between '".$fecha_ini."' and '".$fecha_fin."' ");
        $data= json_decode( json_encode($hoy_t), true);
        foreach ($data as $key ) {
           $hoy_t = $key;
           $resultado_t = implode($hoy_t);                                
        }       

        $hoy_r = DB::connection('retenciones_envio')->select('call spTimbradoHoyRet("'.$iu_usuario.'")');   
        $data_r= json_decode( json_encode($hoy_r), true);
        foreach ($data_r as $key ) {
           $t_hoy = $key;
           $resultado_r = implode($t_hoy);                     
        }  
        $suma =  $resultado_r+$resultado_t;        
        $suma = number_format($suma);                
        return $suma;      
        
       
        
    }
    public function pendientes_hoy(){
        $t_hoy = 0;
        $iu_usuario  = session()->get('no_usuario');
        $fecha_ini = date("Y-m-d").": 00:00:00";
        $fecha_fin = date("Y-m-d").": 23:59:59";
        
        $t_hoy = DB::connection('tr_core_timbrado')->select("SELECT count(*) as timbrado_hoy FROM d_timbrado where iu_usuario = ".$iu_usuario." and fechah_timbrado between '".$fecha_ini."' and '".$fecha_fin."' and enviado = 0");
        $data= json_decode( json_encode($t_hoy), true);
        foreach ($data as $key ) {
           $t_hoy = $key;
           $resultado = implode($t_hoy);                     
           $resultado = number_format($resultado);                
        }
        
        return $resultado;    
        
    }

    public function grafica(){
         $iu_usuario = session()->get('no_usuario');
         $dias_mes   = date("d");

       for($i=1; $i<=$dias_mes; $i++){     
             
           if($i == 1 || $i == 2 || $i == 3|| $i == 4|| $i == 5|| $i == 6|| $i == 7|| $i == 8|| $i == 9){
               $aux = '0';
               $i = $aux.$i;
           }
                    $fecha     = date("Y-m")."-".$i;
                   
                    $grafica_t = DB::select('call spPorDia("'.$iu_usuario.'", "'.$fecha.'")'); 
                    $grafica_r = DB::connection('retenciones_envio')->select('call spTimbradoHoyRet("'.$iu_usuario.'")');                       
                   
                    $datos_t= json_decode( json_encode($grafica_t), true);
                    $datos_r= json_decode( json_encode($grafica_r), true);
                   
                   
                    foreach($datos_t as $l){
                        $dias_t = $l;
                        $resultado_t = implode($dias_t);      
                    }
                   
                    foreach($datos_r as $l){
                        $dias_r = $l;
                        $resultado_r = implode($dias_r);    
                    }
                 $totalxDia[] = $resultado_r + $resultado_t;                                  
            }
            return $totalxDia;    
    }

    public function graficaHistorica($contador){
         $iu_usuario = session()->get('no_usuario');

         
       for($i=1; $i<=31; $i++){     
             
           if($i == 1 || $i == 2 || $i == 3|| $i == 4|| $i == 5|| $i == 6|| $i == 7|| $i == 8|| $i == 9){
               $aux = '0';
               $i = $aux.$i;
           }
                      
                     $fecha    = date("Y-m-$i", strtotime("- $contador month"));
                     $tablat    = "timbrado_historico.".date("Y_m", strtotime("- 1 month"))."_d_timbrado";
                   
                     $timbrado = DB::table($tablat)->select(DB::RAW('count(*) as totalTimbrado'))
                                                      ->where([
                                                         ['iu_usuario', $iu_usuario],
                                                         ['fechah_timbrado', 'LIKE', $fecha.'%']
                                                        ])
                                                      ->get();

                      $retenciones  = DB::connection('retenciones_envio')->table('retenciones_envio.ret_timbrados')->select(DB::RAW('count(*) as totalRetenciones'))
                                                      ->where([
                                                         ['id_usuario', $iu_usuario],
                                                         ['fecha_timbrado', 'LIKE', $fecha.'%']
                                                        ])
                                                      ->get();
                    
                    $datos_t= json_decode( json_encode($timbrado), true);
                    $datos_r= json_decode( json_encode($retenciones), true);
                   
                   
                    foreach($datos_t as $tim){
                        $dias_t = $tim;
                        $resultado_t = implode($dias_t);      
                    }
                   
                    foreach($datos_r as $ret){
                        $dias_r = $ret;
                        $resultado_r = implode($dias_r);    
                    }
                 $totalxDia[] = $resultado_r + $resultado_t; 
            }
                  return $totalxDia;    
                  //dd($timbrado);
                  //dd($retenciones);
    }

    public function totales_complementos(){

         $historico = 0;  
         $iu_usuario  = session()->get('no_usuario');
        
         $historico = DB::connection('historico_reporteador')->select('call spTotalComplementos("'.$iu_usuario.'")');     
         return $historico;  
         exit();
         
         foreach ($historico as $complementos) {
             
             $replace     = $complementos->complemento;
             $complemento = $complementos->TOTAL_COMPLEMENTOS;
             if ($replace == '') {
                $replace = 'CFDI 3.3';
             }
             elseif($replace == 'aerolineas|'){

                $replace = 'Complemento Aerolineas';
             }elseif ($replace == 'certificadodedestruccion|
') {
                 
                 $replace = 'Complemento de Certificado de Destrucción';

             }elseif ($replace == 'ComercioExterior11|
') {
                 $replace = 'Complemento de Comercio Exterior';
             }elseif($replace == 'consumodeCombustibles11|'){

                $replace = 'Complemento de Consumo de Combustibles 1.1';
             }elseif ($replace == 'consumodecombustibles|') {
                 $replace = 'Complemento de Consumo de Combustibles 1.0';
             }elseif ($replace == 'detallista|
') {
                 $replace = 'Complemento Detallista';
             }elseif ($replace == 'divisas|') {
                 $replace = 'Complemento de Divisas';
             }elseif ($replace == 'donat11|') {
                 $replace = 'Complemento de Donatarias 1.1';
             }elseif ($replace == 'ecc11|') {
                 $replace = 'Complemento de Estado Cuenta de Combustibles 1.1';
             }elseif ($replace == 'ecc12|') {
                 $replace = 'Complemento de Estado Cuenta de Combustibles 1.2';
             }elseif ($replace == 'GastosHidrocarburos10|') {
                 $replace = 'Complemento de Gastos de Hidrocarburos';
             }elseif ($replace == 'GastosHidrocarburos10|
') {
                 $replace = 'Complemento de Gastos de Hidrocarburos';
             }elseif ($replace == 'IdentificacionRecursoGastos|
') {
                 $replace = 'Complemento de Identificación de Recursos';
             }elseif ($replace == 'iedu|') {
                 $replace = 'Complemento concepto de Instituciones Educativas ';
             }elseif ($replace == 'implocal|') {
                 $replace = 'Complemento de Impuestos Locales y/o federales';
             }elseif ($replace == 'ine11|
') {
                 $replace = 'Complemento INE';
             }elseif ($replace == 'IngresosHidrocarburos|
') {
                 $replace = 'Complemento de Ingresos Hidrocarburos';
             }elseif ($replace == 'leyendasFisc|') {
                 $replace = 'Complemento Leyendas Fiscales';
             }elseif ($replace == 'nomina12|') {
                 $replace = 'Complemento de Nomina 1.2';
             } elseif ($replace == 'notariospublicos|') {
                 $replace = 'Complemento Notarios Publicos ';
             }elseif ($replace == 'obrasarteantiguedades|
') {
                 $replace = 'Complemento Obras de Arte y Antiguedades';
             }elseif ($replace == 'pagoenespecie|') {
                 $replace = 'Complemento Pago en Especie';
             }elseif ($replace == 'pago10|') {
                 $replace = 'Complemento Recepción de Pagos';
             }elseif ($replace == 'pfic|') {
                 $replace = 'Complemento de Personas Fisicas Integrantes de Coordinados';
             }elseif ($replace == 'renovacionysustitucionvehiculos|
') {
                 $replace = 'Complemento de Renovación y sustitución de vehículos';
             }elseif ($replace == 'servicioparcialconstruccion|
') {
                 $replace = 'Complemento Servicios parciales de construcción';
             }elseif ($replace == 'spei|
') {
                 $replace = 'Complemento SPEI';
             }elseif ($replace == 'terceros11|
') {
                 $replace = 'Complemento concepto por cuenta de Terceros';
             }elseif ($replace == 'TuristaPasajeroExtranjero|') {
                 $replace = 'Complemento Turista pasajero extranjero'; 
             }elseif ($replace == 'valesdedespensa|
') {
                 $replace = 'Complemento de Vales de despensa';
             }elseif ($replace == 'vehiculousado|
') {
                 $replace = 'Complemento de Vehículo usado';
             }elseif ($replace == 'ventavehiculos11|
') {
                 $replace = 'Complemento concepto Venta de vehiculos nuevos 1.1';
             }elseif ($replace == 'cfdiregistrofiscal|') {
                 $replace = "";
                 $complemento = 0;
             }elseif ($replace == 'gceh|') {
                 $replace = "Complemento gastos hidrocarburos";
             }elseif ($replace == 'ieeh|') {
                 $replace = "Complemento ingresos Hidrocarburos";
             }
             elseif ($replace == 'cce11|') {
                 $replace = "Complemento de comercio exterior 1.1";
             }

             /*$totalComplementos[] = array (
                                           'complemento' => $replace,
                                           'TOTAL'       => number_format($complemento)
                                     );*/

         }

         //dd($totalComplementos);
         /*foreach ($totalComplementos as $key => $value) { if ($value["TOTAL"] == 0) {  unset($totalComplementos[$key]); } } 
         return $totalComplementos;        */
    }

    public function promedio_historico(){

        $iu_usuario  = session()->get('no_usuario');       
        $promedio    = DB::connection('historico_reporteador')->select('call spPromedio("'.$iu_usuario.'")');               
        $data        = json_decode( json_encode($promedio), true);

        foreach ($data as $key ) {
            $prom = $key;
            $resultado = implode($prom);    
            $resultado = number_format($resultado);                                   
         }    

        return $resultado;
   }

}
