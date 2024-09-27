<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VeiculoController extends Controller
{
    public function getVeiculos(Request $request)
    {
            // $dados = $request->query();

            $veiculos = DB::table('VEICULOS as veic')
                ->select(
                'veic.modelo',
                'veic.placa',
                'veic.capacidade',
                'veic.dt_ultim_manu',
                )
                ->get();

            return response($veiculos, 200);  
    }
    
    public function insertVeiculos(Request $request)
{
    $veiculo = DB::table('VEICULOS')->insert([
        'modelo' => $request->input('modelo'),
        'placa' => $request->input('placa'),
        'ano' => $request->input('ano'),
        'capacidade' => $request->input('capacidade'),
        'dt_prox_manu' => $request->input('dataProxManutencao'),
        'dt_ultim_manu' => $request->input('dataUltManutencao'),
        'empresa' => $request->input('empresa'),
        'motorista' => $request->input('motorista'),
        'tipo_veiculo' => $request->input('tipoVeiculo'),
    ]);

    return response()->json(['success' => true, 'message' => 'Veículo cadastrado com sucesso!'], 200);
}
}
