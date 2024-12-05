<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelatoriosController extends Controller
{
    public function getRelatorioRotas(Request $request)
{
    // dd($request->all());
    $cod_veiculo = $request->query('cod_veiculo');

    $rotas = DB::table('VEICULOS as v')
        ->join('EMPRESAS as e', 'v.cod_empresa', '=', 'e.cod_empresa')
        ->join('USUARIOS as u', 'v.cod_motorista', '=', 'u.cod_usuario')
        ->join('ROTAS as r', 'v.cod_veiculo', '=', 'r.cod_veiculo')
        ->join('PARTIDAS as p', 'r.cod_partida', '=', 'p.cod_partida')
        ->join('CHEGADAS as c', 'r.cod_chegada', '=', 'c.cod_chegada')
        ->select(
            'v.cod_veiculo',
            'v.placa',
            'v.modelo',
            'v.placa',
            'e.nome as empresa',
            'u.cpf',
            'u.email',
            'u.nome as motorista',
            'r.cod_rota',
            'r.obs_rota',
            'r.servico_exec',
            'p.data_hora_partida',
            'c.data_hora_chegada'
        )
        ->where('v.cod_veiculo', $cod_veiculo) 
        ->get();

    return response()->json($rotas, 200);
}

}