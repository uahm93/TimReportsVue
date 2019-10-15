<?php

namespace App\Exports;

use App\d_timbrado;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
//use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WhithHeadings;

class BitacoraExport implements FromQuery, ShouldQueue
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function query()
    {
        set_time_limit(620);
        ini_set('memory_limit', '-1');
        $rfc_emisor = session()->get('rfc_emisor_bitacora');
        $uuid       = session()->get('uuid_bitacora');
        $estado     = session()->get('estado_bitacora');
        $error      = session()->get('error_bitacora');
        $iu_usuario = session()->get('no_usuario');

        if ($rfc_emisor == "null") {
            $rfc_emisor = "";
        }
        if ($uuid       == "null") {
            $uuid = "";
        }
        if ($estado     == "null") {
            $estado = "";
        }
        if ($error      == "null") {
            $error = "";
        }

        if ($estado == "codigo") {

            return  $historico = DB::table('d_bitacora')
                ->select('rfc_emisor', 'rfc_receptor', 'fechah', 'respuesta', 'codigo')
                ->where([
                    ['iu_usuario', $iu_usuario],
                    ['respuesta',  'LIKE', $error . '%'],
                ])
                ->orderby('d_bitacora.fechah');
        } elseif ($estado == 'fallido') {

            return  $historico = DB::table('d_bitacora')
                ->select('rfc_emisor', 'rfc_receptor', 'fechah', 'respuesta', 'codigo')
                ->where([
                    ['iu_usuario', $iu_usuario],
                    ['codigo',  '!=', 'EXITO'],
                ])
                ->orderby('d_bitacora.fechah');
        } elseif ($estado == 'EXITO') {

            return  $historico = DB::table('d_timbrado')
                ->join('d_bitacora', 'd_timbrado.transaccion', '=', 'd_bitacora.transaccion')
                ->select('d_timbrado.uuid', 'd_bitacora.rfc_emisor', 'd_bitacora.rfc_receptor', 'd_bitacora.fechah', 'd_bitacora.respuesta', 'd_bitacora.respuesta', 'd_bitacora.codigo')
                ->where([
                    ['d_bitacora.iu_usuario', $iu_usuario],
                    ['d_bitacora.codigo',     'EXITO']
                ])
                ->orderby('d_bitacora.fechah');
        } elseif ($estado == "snv") {

            return  $historico = DB::table('d_timbrado')
                ->join('d_bitacora', 'd_timbrado.transaccion', '=', 'd_bitacora.transaccion')
                ->select('d_timbrado.uuid', 'd_bitacora.rfc_emisor', 'd_bitacora.rfc_receptor', 'd_bitacora.fechah', 'd_bitacora.respuesta', 'd_bitacora.respuesta', 'd_bitacora.codigo')
                ->where([
                    ['d_bitacora.iu_usuario', $iu_usuario],
                    ['d_timbrado.uuid',  'LIKE', $uuid . '%'],
                    ['d_timbrado.rfc_emisor',  'LIKE', $rfc_emisor . '%']
                ])
                ->orderby('d_bitacora.fechah');
        }
    }
}
