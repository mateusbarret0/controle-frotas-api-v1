<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelatoriosController extends Controller
{
    public function getRelatorioRotas(Request $request)
{
    $placa = $request->query('placa');

    $rotas = DB::table('ROTAS as r')
        ->join('VEICULOS as v', 'r.PLACA', '=', 'v.PLACA')
        ->join('USUARIOS as u', 'v.motorista', '=', 'u.nome') 
        ->select(
            'r.ID as rota_id',
            'r.COD_ROTA',
            'r.CEP_PARTIDA',
            'r.CEP_CHEGADA',
            'r.NUMERO_PARTIDA',
            'r.NUMERO_CHEGADA',
            'r.DESCRICAO_PARTIDA',
            'r.DESCRICAO_CHEGADA',
            'r.COMPLEMENTO_PARTIDA',
            'r.COMPLEMENTO_CHEGADA',
            'r.RUA_PARTIDA',
            'r.BAIRRO_PARTIDA',
            'r.CIDADE_PARTIDA',
            'r.ESTADO_PARTIDA',
            'r.RUA_CHEGADA',
            'r.BAIRRO_CHEGADA',
            'r.CIDADE_CHEGADA',
            'r.ESTADO_CHEGADA',
            'r.DATA_HORA_INICIO',
            'r.DATA_HORA_CHEGADA',
            'r.status as rota_status',
            'r.DESCRICAO_ROTA',
            'r.DESVIOS',
            'r.PARADAS',
            'r.ROTA_ALTERNATIVA',
            'r.INCIDENTES',

            'v.id as veiculo_id',
            'v.modelo',
            'v.placa as veiculo_placa',
            'v.ano',
            'v.capacidade',
            'v.dt_prox_manu',
            'v.dt_ultim_manu',
            'v.empresa',
            'v.motorista',
            'v.tipo_veiculo',
            'v.status as veiculo_status',

            'u.nome as motorista_nome',
            'u.cpf as motorista_cpf',
            'u.email as motorista_email',
            'u.status as motorista_status',
            'u.tipo as motorista_tipo'
        )
        ->where('v.placa', $placa) 
        ->get();

    return response()->json($rotas, 200);
}

}