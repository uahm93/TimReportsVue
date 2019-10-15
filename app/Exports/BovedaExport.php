<?php

namespace App\Exports;
use App\d_usuario;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class BovedaExport implements  FromQuery, ShouldQueue
{
    use Exportable, SerializesModels;

    /**
    * @return \Illuminate\Support\Collection
    */

    public function query()
        
    {  
        $fechainicio = session()->get('fechainicio_boveda');
        $fechafin    = session()->get('fechafin_boveda');
        $estado      = session()->get('estado_boveda');
        
        $iu_usuario  = session()->get('no_usuario');
        $hoy=date("Y-m-d", strtotime("-4 day"));
        

        $fechaI = substr($fechainicio, 0, 10);
        $horaI  = substr($fechainicio, 11, 8);
        
        $fechaF = substr($fechafin, 0, 10);
        $horaF  = substr($fechafin, 11, 8);

        
        for($i=$fechaI;$i<=$fechaF;$i = date("Y-m-d", strtotime($i ."+ 1 day")))
        {   
            $aux = date("Y-m-d", strtotime($i));
            if($aux<=$hoy)
            {
                 $año=substr($i,0,4);
                 $mes=substr($i,5,2);
                 $tablat[]="timbrado_historico.{$año}_{$mes}_d_timbrado";
            }else{
                 $tablat[]="tr_core_timbrado.d_timbrado";
            }

                      
        }
        
         $resultado = array_unique($tablat);

            foreach ($resultado as $tabla) {

                return DB::table($tabla)->select('rfc_emisor', 'rfc_receptor', 'serie', 'folio', 'monto', 'uuid', 'fechah_emision','fechah_timbrado')
                                     ->where('iu_usuario', $iu_usuario)
                                     ->whereBetween('fechah_timbrado',  [''.$fechaI.' '.$horaI.'' , ''.$fechaF.' '.$horaF.''])
                                     ->orderby('fechah_timbrado');
                
            } 
        
    }

    
}
