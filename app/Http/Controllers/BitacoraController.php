<?php

namespace App\Http\Controllers;

use Excel;
use Illuminate\Http\Request;
use App\Exports\BitacoraExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Exports\BitacoraExportSubCuentas;


class BitacoraController extends Controller
{


    public function iuUsuario()
    {

        //obtiene el iu_usuario de todos los hijos de la cuenta padre
        $iu_usuario  = session()->get('no_usuario');
        //$padre       = session()->get('user');
        $consulta    = DB::table('d_usuario')->select('iu_usuario', 'usuario')
            ->where('alta_por', '=', $iu_usuario)
            ->orderby('iu_usuario')
            ->get();
        if ($consulta == '[]') {
            return session()->get('no_usuario');
        } else {
            /*$principal[] = ['iu_usuario' => $iu_usuario, 'usuario' => $padre];  //Cuenta padre
            $lista = json_decode( json_encode($consulta), true);     
            return array_merge($lista, $principal); //Agrega cuenta padre al arreglo para poder obtener su reporte*/
            return $consulta;
        }
    }

    public function subCuentasBita($rfc_emisor, $uuid, $estado, $error)
    {

        ini_set('memory_limit', '-1');
        set_time_limit(920);
        session()->put('rfc_emisor_bitacora', $rfc_emisor);
        session()->put('uuid_bitacora', $uuid);
        session()->put('estado_bitacora', $estado);
        session()->put('error_bitacora', $error);



        $iu_usuario = session()->get('no_usuario');
        $emisores   = new BitacoraController;
        $usuarios       = $emisores->iuUsuario();


        $data = json_decode( json_encode($usuarios), true);
        array_push($data, array('iu_usuario' => $iu_usuario));
        $iu_usuario = session()->get('no_usuario');

        
        $bitacora   = [];
        if ($rfc_emisor == "null") {
            $rfc_emisor = "";
        }
        if ($uuid == "null") {
            $uuid = "";
        }
        if ($estado == "null") {
            $estado = "";
        }
        if ($error == "null") {
            $error = "";
        }
        if($estado == "uuid"){
            $estado = 'snv';
        }

        if ($estado == "codigo") {

            foreach ($data as $usuario) {

                
                $iu_usuario = $usuario['iu_usuario'];


                $historico = DB::table('d_bitacora')
                    ->select('rfc_emisor', 'rfc_receptor', 'fechah', 'respuesta', 'codigo', 'xml as PATH_BITACORA')
                    ->where([
                        ['iu_usuario', $iu_usuario],
                        ['respuesta',  'LIKE', $error . '%'],
                    ])
                    ->orderby('d_bitacora.fechah')
                    ->get();
                foreach ($historico as $key) {

                    $bitacora[] = array(

                        'rfc_emisor' => $key->rfc_emisor,
                        'rfc_receptor' => $key->rfc_receptor,
                        'fechah' => $key->fechah,
                        'respuesta' => $key->respuesta,
                        'codigo' => $key->codigo,
                        'PATH_BITACORA' => $key->PATH_BITACORA,
                    );
                }
            }

            
        } elseif ($estado == 'fallido') {

            foreach ($data as $usuario) {

                $iu_usuario = $usuario['iu_usuario'];

                $historico = DB::table('d_bitacora')
                    ->select('rfc_emisor', 'rfc_receptor', 'fechah', 'respuesta', 'codigo', 'xml as PATH_BITACORA')
                    ->where([
                        ['iu_usuario', $iu_usuario],
                        ['codigo',  '!=', 'EXITO'],
                    ])
                    ->orderby('d_bitacora.fechah')
                    ->get();
                foreach ($historico as $key) {

                    $bitacora[] = array(

                        'rfc_emisor' => $key->rfc_emisor,
                        'rfc_receptor' => $key->rfc_receptor,
                        'fechah' => $key->fechah,
                        'respuesta' => $key->respuesta,
                        'codigo' => $key->codigo,
                        'PATH_BITACORA' => $key->PATH_BITACORA,
                    );
                }
            }
            
        } elseif ($estado == 'EXITO') {

            foreach ($data as $usuario) {

                $iu_usuario = $usuario['iu_usuario'];

                $historico = DB::table('d_timbrado')
                    ->join('d_bitacora', 'd_timbrado.transaccion', '=', 'd_bitacora.transaccion')
                    ->select('d_timbrado.uuid', 'd_bitacora.rfc_emisor', 'd_bitacora.rfc_receptor', 'd_bitacora.fechah', 'd_bitacora.respuesta', 'd_bitacora.respuesta', 'd_bitacora.codigo', 'd_timbrado.xml as PATH_TIMBRADO', 'd_bitacora.xml as PATH_BITACORA')
                    ->where([
                        ['d_bitacora.iu_usuario', $iu_usuario],
                        ['d_bitacora.codigo',     'EXITO']
                    ])
                    ->orderby('d_bitacora.fechah')
                    ->get();
                foreach ($historico as $key) {

                    $bitacora[] = array(

                        'uuid'    => $key->uuid,
                        'rfc_emisor' => $key->rfc_emisor,
                        'rfc_receptor' => $key->rfc_receptor,
                        'fechah' => $key->fechah,
                        'respuesta' => $key->respuesta,
                        'codigo' => $key->codigo,
                        'PATH_TIMBRADO' => $key->PATH_TIMBRADO,
                        'PATH_BITACORA' => $key->PATH_BITACORA,
                    );
                }
            }

            
        } elseif ($estado == "snv") {
            

            foreach ($data as $usuario) {

                $iu_usuario = $usuario['iu_usuario']; 

                $historico = DB::table('d_timbrado')
                    ->join('d_bitacora', 'd_timbrado.transaccion', '=', 'd_bitacora.transaccion')
                    ->select('d_timbrado.uuid', 'd_bitacora.rfc_emisor', 'd_bitacora.rfc_receptor', 'd_bitacora.fechah', 'd_bitacora.respuesta', 'd_bitacora.respuesta', 'd_bitacora.codigo', 'd_timbrado.xml as PATH_TIMBRADO', 'd_bitacora.xml as PATH_BITACORA')
                    ->where([
                        ['d_bitacora.iu_usuario', $iu_usuario],
                        ['d_timbrado.uuid',  'LIKE', $uuid . '%'],
                        ['d_timbrado.rfc_emisor',  'LIKE', $rfc_emisor . '%']
                    ])
                    ->orderby('d_bitacora.fechah')
                    ->get();

                foreach ($historico as $key) {

                    $bitacora[] = array(

                        'uuid'    => $key->uuid,
                        'rfc_emisor' => $key->rfc_emisor,
                        'rfc_receptor' => $key->rfc_receptor,
                        'fechah' => $key->fechah,
                        'respuesta' => $key->respuesta,
                        'codigo' => $key->codigo,
                        'PATH_TIMBRADO' => $key->PATH_TIMBRADO,
                        'PATH_BITACORA' => $key->PATH_BITACORA,
                    );
                }
            }
            
        }else{ return "Vacio"; }

        if(!$bitacora){
            
            return "Vacio"; 
        }else{
            return $bitacora;

        }
    }

    public function bitacora($rfc_emisor, $uuid, $estado, $error)
    {
        set_time_limit(920);
        ini_set('memory_limit', '-1');
        session()->put('rfc_emisor_bitacora', $rfc_emisor);
        session()->put('uuid_bitacora', $uuid);
        session()->put('estado_bitacora', $estado);
        session()->put('error_bitacora', $error);
        $historico = [];


        if ($rfc_emisor == "null") {
            $rfc_emisor = "";
        }
        if ($uuid == "null") {
            $uuid = "";
        }
        if ($estado == "null") {
            $estado = "";
        }
        if ($error == "null") {
            $error = "";
        }if($estado == "uuid"){
            $estado = 'snv';
        }

        $iu_usuario  = session()->get('no_usuario');
        if ($estado == "codigo") {

             $historico = DB::table('d_bitacora')
                ->select('rfc_emisor', 'rfc_receptor', 'fechah', 'respuesta', 'codigo', 'xml as PATH_BITACORA')
                ->where([
                    ['iu_usuario', $iu_usuario],
                    ['respuesta',  'LIKE', $error . '%'],
                ])
                ->orderby('d_bitacora.fechah')
                ->get();
        } elseif ($estado == 'fallido') {
             $historico = DB::table('d_bitacora')
                ->select('rfc_emisor', 'rfc_receptor', 'fechah', 'respuesta', 'codigo', 'xml as PATH_BITACORA')
                ->where([
                    ['iu_usuario', $iu_usuario],
                    ['codigo',  '!=', 'EXITO'],
                ])
                ->orderby('d_bitacora.fechah')
                ->get();
        } elseif ($estado == 'EXITO') {
             $historico = DB::table('d_timbrado')
                ->join('d_bitacora', 'd_timbrado.transaccion', '=', 'd_bitacora.transaccion')
                ->select('d_timbrado.uuid', 'd_bitacora.rfc_emisor', 'd_bitacora.rfc_receptor', 'd_bitacora.fechah', 'd_bitacora.respuesta', 'd_bitacora.respuesta', 'd_bitacora.codigo', 'd_timbrado.xml as PATH_TIMBRADO', 'd_bitacora.xml as PATH_BITACORA')
                ->where([
                    ['d_bitacora.iu_usuario', $iu_usuario],
                    ['d_bitacora.codigo',     'EXITO']
                ])
                ->orderby('d_bitacora.fechah')
                ->get();
        } elseif ($estado == "snv") {

             $historico = DB::table('d_timbrado')
                ->join('d_bitacora', 'd_timbrado.transaccion', '=', 'd_bitacora.transaccion')
                ->select('d_timbrado.uuid', 'd_bitacora.rfc_emisor', 'd_bitacora.rfc_receptor', 'd_bitacora.fechah', 'd_bitacora.respuesta', 'd_bitacora.respuesta', 'd_bitacora.codigo')
                ->where([
                    ['d_bitacora.iu_usuario', $iu_usuario],
                    ['d_timbrado.uuid',  'LIKE', $uuid . '%'],
                    ['d_timbrado.rfc_emisor',  'LIKE', $rfc_emisor . '%']
                ])
                ->orderby('d_bitacora.fechah')
                ->get();
        }else{ 
            return "Vacio"; 
        }

        //$file = escapeshellcmd($data);
        //$cmd = ("echo {$data} | sed 's/},/},\n/g' | xargs -n 1 echo | cut -f1,2,3,4,5,6 -d',' | sed 's/\[\|{uuid:\|rfc_emisor:\|rfc_receptor:\|fechah:\|respuesta:\|codigo://g' > datosVariableTer.xlsx'");
        //shell_exec($cmd);  
        //var_dump($data);
        //file_put_contents("totales_emisor_abril_2019.csv", $data, FILE_APPEND | LOCK_EX);  

        if($historico == '[]'){
            return "Vacio"; 
        }else{
            return $historico;

        }

    }

    public function excel($tipo)
    {
        if($tipo == 'CnSubcuentas'){
             
          return Excel::download(new BitacoraExportSubCuentas, 'ReporteExcel.csv');    

        }else{
            
          return Excel::download(new BitacoraExport, 'ReporteExcel.csv');

      }
    }

    public function xml($ruta, $uuid)
    {
        $iu_usuario  = session()->get('no_usuario');
        $uuid;
        if (isset($iu_usuario)) {

            if ($uuid == "undefined") {
                $uuid = "XML_sin_timbrar";
            }
            $path = base64_decode($ruta);
            header('Content-Disposition: attachment; filename="' . $uuid . '.xml"');
            header('Content-type: text/xml');
            echo '<?xml version="1.0" encoding="UTF-8"?>' . file_get_contents($path);
        } else {
            redirect('/');
        }
    }
}
