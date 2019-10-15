<?php

namespace App\Http\Controllers;


use Auth;
use SoapClient;
use App\d_usuario;
use App\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use nusoap_client;
use nusoap;



class UsuarioController extends Controller
{   
    protected $servicio = "https://timbrado33.expidetufactura.com.mx:8447/TimbradoBridgeProduccion/TimbradoService?wsdl";    
   
    public function obtenerUsuario()
    {

        $iu_usuario  = session()->get('no_usuario');
        try{
        $consulta    = DB::table('d_usuario')->select('*')
            ->where('iu_usuario', '=', $iu_usuario)
            ->get();
            } catch (\Illuminate\Database\QueryException $e) {

                return view('errors.todos');
            }
        return $consulta;
    }

    public function obtenerNotificaciones()
    {


        //return DB::connection('Notificaciones')->select("SELECT * FROM Notificaciones");
    }

    public function ultimaFactura()
    {

        $contador = '';
        $consulta = 0;
        $aux      = 0;
        $usuario  = session()->get('user');


        while ($contador != 'stop') {

            $aux++;
            $tabla    = 'timbrado_historico.' . date("Y_m_", strtotime("- $aux month")) . 'd_timbrado';
            $consulta =  DB::table($tabla)->select('fechah_timbrado', 'monto', 'xml')
                ->where([
                    ['rfc_emisor', '=', 'CCC1007293K0'],
                    ['rfc_receptor', $usuario],
                  ])
                ->get();
            if ($consulta != '[]') {

                $contador = 'stop';
                return $consulta;
            }
            if($tabla == 'timbrado_historico.2018_01_d_timbrado'){

              return '[]';
            }
        }
    }

    public function obtenerFacturas()
    {
         $usuario = session()->get('user');
         $fechaI = '2018-01-01';
         $fechaF = date("Y-m-d", strtotime("- 1 month"));
         $facturas = [];

        for ($i = $fechaI; $i <= $fechaF; $i = date("Y-m-d", strtotime($i . "+ 1 month"))) {
            $año  = substr($i, 0, 4);
            $mes  = substr($i, 5, 2);
            $like = $año.'-'.$mes;
            $tablat = "timbrado_historico.{$año}_{$mes}_d_timbrado"; 
            
            $total = DB::table($tablat)->select('iu_timbrado', 'fechah_timbrado', 'monto', 'xml')
                ->where([
                    ['rfc_emisor', '=', 'CCC1007293K0'],
                    ['rfc_receptor', $usuario],
                    ['fechah_timbrado', 'LIKE', $like.'%']
                ])
                ->groupBy('iu_timbrado')
                ->get();

            foreach ($total as $key ) {

                                 $facturas[] = array (
                                       'iu_timbrado'    => $key->iu_timbrado,
                                       'fechah_timbrado' => $key->fechah_timbrado,
                                       'monto' => number_format($key->monto),
                                       'xml' => $key->xml,
                                 );
                            }    
        }
         
        return $facturas;

    }

    public function descargarXml($ruta, $fecha)
    {   

       $path = base64_decode($ruta);

       $fichero = file_get_contents($path, true);

       $arreglo    = array('usuario'  => "God", 
                    'contrasena'    => "abracadabra",
                    'archivo'       => base64_encode($fichero), 
                    'plantilla'     => "1",
                    'observaciones' => "cero",
 );

try {

   $WebService = new nusoap_client($this->servicio, 'wsdl');
   $WebService->soap_defencoding = 'UTF-8';
   $WebService->decode_utf8 = FALSE;
   $result = $WebService->call('obtenerPDFyXML', $arreglo);
   $response = json_decode(json_encode($result), True);                            
                            
    foreach ($response as $pdf ) {
        $pdfCod = $pdf['pdf'];
    }
   
   $decoded = base64_decode($pdfCod);
   $file = 'Factura-'.$fecha.'.pdf';
    file_put_contents($file, $decoded);

    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="Factura-'.$fecha.'.pdf"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        }
    

} catch (Exception $e) {
    echo 'Excepción capturada: ',  $report($e), "\n";
}


    }


}
