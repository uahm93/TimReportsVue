<?php

namespace App\Http\Controllers;

use Excel;
use Zipper;
use Illuminate\Http\Request;
use App\Exports\BovedaExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Exports\BovedaExportSubCuentas;


class BovedaController extends Controller
{

    public function boveda($fechainicio, $fechafin, $estado)
    {
        set_time_limit(920);
        ini_set('memory_limit', '-1');
        session()->put('fechainicio_boveda', $fechainicio);
        session()->put('fechafin_boveda', $fechafin);
        session()->put('estado_boveda', $estado);
        $boveda = [];


        $iu_usuario  = session()->get('no_usuario');
        $hoy = date("Y-m-d", strtotime("-4 day"));


        $fechaI = substr($fechainicio, 0, 10);
        $horaI  = substr($fechainicio, 11, 8);

        $fechaF = substr($fechafin, 0, 10);
        $horaF  = substr($fechafin, 11, 8);


        //Recorre el rango de fechas seleccionado por el usuario
        for ($i = $fechaI; $i <= $fechaF; $i = date("Y-m-d", strtotime($i . "+ 1 day"))) {
            $aux = date("Y-m-d", strtotime($i));
            if ($aux <= $hoy) {
                $año    = substr($i, 0, 4);
                $mes    = substr($i, 5, 2);
                $tablat[] = "timbrado_historico.{$año}_{$mes}_d_timbrado";
            } else {
                $tablat[] = "tr_core_timbrado.d_timbrado";
            }
        }
        if (!isset($tablat)) {
            return view('errors.todos');
        }


        //obtiene las tablas que se van a consultar y se eliminan las repetidas
        $resultado = array_unique($tablat);


        //Recorre las tablas obtenidas y ejecuta la consulta 
        foreach ($resultado as $tabla) {

            try {
                $total = DB::table($tabla)->select('rfc_emisor', 'rfc_receptor', 'uuid', 'fechah_timbrado', 'monto', 'serie', 'xml', 'fechah_emision')
                    ->where('iu_usuario', $iu_usuario)
                    ->whereBetween('fechah_timbrado',  ['' . $fechaI . ' ' . $horaI . '', '' . $fechaF . ' ' . $horaF . ''])
                    ->orderby('fechah_timbrado')
                    ->get();
            } catch (\Illuminate\Database\QueryException $e) {

                return view('errors.todos');
            }
            foreach ($total as $key) {

                $boveda[] = array(
                    'rfc_emisor'    => $key->rfc_emisor,
                    'rfc_receptor' => $key->rfc_receptor,
                    'uuid'         => $key->uuid,
                    'fechah_timbrado' => $key->fechah_timbrado,
                    'monto' => number_format($key->monto),
                    'serie' => $key->serie,
                    'xml' => $key->xml,
                    'fechah_emision' => $key->fechah_emision,
                );
            }
        }

        if (!$boveda) {
            return "Vacio";
        } else {
            return $boveda;
        }
    }
    public function bovedaSubcuentas($fechainicio, $fechafin, $estado)
    {
        set_time_limit(920);
        ini_set('memory_limit', '-1');
        session()->put('fechainicio_boveda', $fechainicio);
        session()->put('fechafin_boveda', $fechafin);
        session()->put('estado_boveda', $estado);
        $boveda = [];

        //Obtiene las cuentas hijo
        $iu_usuario = session()->get('no_usuario');
        $data = DB::table('d_usuario')->select('iu_usuario')
            ->where('alta_por', '=', $iu_usuario)
            ->orderby('iu_usuario')
            ->get();


        $usuarios = json_decode(json_encode($data), true);


        //Agrega la cuenta padre a la lista para posteriormente obtener sus reportes
        array_push($usuarios, array('iu_usuario' => $iu_usuario));


        $hoy = date("Y-m-d", strtotime("-4 day"));

        $fechaI = substr($fechainicio, 0, 10);
        $horaI  = substr($fechainicio, 11, 8);

        $fechaF = substr($fechafin, 0, 10);
        $horaF  = substr($fechafin, 11, 8);


        foreach ($usuarios as $usuario) {

            $iu_usuario = $usuario['iu_usuario'];

            for ($i = $fechaI; $i <= $fechaF; $i = date("Y-m-d", strtotime($i . "+ 1 day"))) {
                $aux = date("Y-m-d", strtotime($i));
                if ($aux <= $hoy) {
                    $año    = substr($i, 0, 4);
                    $mes    = substr($i, 5, 2);
                    $tablat[] = "timbrado_historico.{$año}_{$mes}_d_timbrado";
                } else {
                    $tablat[] = "tr_core_timbrado.d_timbrado";
                }
            }
            if (!isset($tablat)) {
                return view('errors.todos');
            }
            $resultado = array_unique($tablat);


            foreach ($resultado as $tabla) {

                try {
                    $total = DB::table($tabla)->select('rfc_emisor', 'rfc_receptor', 'uuid', 'fechah_timbrado', 'monto', 'serie', 'xml', 'fechah_emision')
                        ->where('iu_usuario', $iu_usuario)
                        ->whereBetween('fechah_timbrado',  ['' . $fechaI . ' ' . $horaI . '', '' . $fechaF . ' ' . $horaF . ''])
                        ->orderby('fechah_timbrado')
                        ->get();
                } catch (\Illuminate\Database\QueryException $e) {

                    return view('errors.todos');
                }

                foreach ($total as $key) {

                    $boveda[] = array(
                        'rfc_emisor'    => $key->rfc_emisor,
                        'rfc_receptor' => $key->rfc_receptor,
                        'uuid'         => $key->uuid,
                        'fechah_timbrado' => $key->fechah_timbrado,
                        'monto' => number_format($key->monto),
                        'serie' => $key->serie,
                        'xml' => $key->xml,
                        'fechah_emision' => $key->fechah_emision,
                    );
                }
            }
        }
        if (!$boveda) {

            return "Vacio";
        } else {
            return $boveda;
        }
    }

    public function zipBoveda($tipo)
    {

        $fechainicio = session()->get('fechainicio_boveda');
        $fechafin    = session()->get('fechafin_boveda');
        $estado      = session()->get('estado_boveda');

        if ($tipo == 'CnSubcuentas') {

            $rutas       = new BovedaController;
            $mostrar     = $rutas->bovedaSubcuentas($fechainicio, $fechafin, $estado);
        } else {

            $rutas       = new BovedaController;
            $mostrar     = $rutas->boveda($fechainicio, $fechafin, $estado);
        }

        $array       = json_decode(json_encode($mostrar), True);

        foreach ($array as $key) {

            $rutas = $key['xml'];
            /*Añadimos la ruta donde se encuentran los archivos que queramos comprimir,
          en este ejemplo comprimimos todos los que se encuentran en la carpeta 
          storage/app/public*/
            $files[] = $rutas;
        }
        $arhivo  = session()->get('user') . '-' . $fechainicio . '-' . $fechafin;

        /* Le indicamos en que carpeta queremos que se genere el zip y los comprimimos*/
        Zipper::make(storage_path('app/public/zips/' . $arhivo . '.zip'))->add($files)->close();

        /* Por último, si queremos descargarlos, indicaremos la ruta del archiv, su nombre
         y lo descargaremos*/

        return response()->download(storage_path('app/public/zips/' . $arhivo . '.zip'));
        //$path = storage_path('app/public/zips/'.$arhivo.'.zip');
        //unlink($path);


    }

    public function excelBoveda($tipo)
    {

        set_time_limit(920);
        ini_set('memory_limit', '-1');
        $fechainicio = session()->get('Fecha');

        if ($tipo == 'CnSubcuentas') {

            return Excel::download(new BovedaExportSubCuentas, 'ReporteExcel-' . $fechainicio . '.csv');
        } else {

            return Excel::download(new BovedaExport, 'ReporteExcel-' . $fechainicio . '.csv');
        }
    }
}
