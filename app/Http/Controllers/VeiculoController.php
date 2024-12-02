<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VeiculoController extends Controller
{
    public function getVeiculos(Request $request)
    {
        $searchTerm = $request->input('searchTerm');

        $veiculos = DB::table('VEICULOS as veic')
            ->select(
                'veic.cod_veic',
                'veic.modelo',
                'veic.placa',
                'veic.ano',
                'veic.capacidade',
                'veic.dt_prox_manu',
                'veic.dt_ultim_manu',
                'veic.empresa',
                'veic.motorista',
                'veic.tipo_veiculo',
                'veic.status'
            );

        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');

            $veiculos->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('veic.modelo', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('veic.placa', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('veic.motorista', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $veiculos = $veiculos->get();

        return response($veiculos, 200);
    }


    public function insertVeiculos(Request $request)
    {
        $lastCodVeiculo = DB::table('VEICULOS')->max('cod_veic');
        $newCodVeiculo = $lastCodVeiculo + 1;
        $veiculo = DB::table('VEICULOS')->insert([
            'cod_veic' => $newCodVeiculo,
            'modelo' => $request->input('modelo'),
            'placa' => $request->input('placa'),
            'ano' => $request->input('ano'),
            'capacidade' => $request->input('capacidade'),
            'dt_prox_manu' => $request->input('dataProxManutencao'),
            'dt_ultim_manu' => $request->input('dataUltManutencao'),
            'empresa' => $request->input('empresa'),
            'motorista' => $request->input('motorista'),
            'tipo_veiculo' => $request->input('tipoVeiculo'),
            'status' => 'disponivel',
        ]);

        return response()->json(['success' => true, 'message' => 'Veículo cadastrado com sucesso!'], 200);
    }
    public function deleteVeiculos(Request $request)
    {
        $placa = $request->input('placa');

        if (!$placa) {
            return response()->json(['success' => false, 'message' => 'Placa do veículo não fornecido'], 400);
        }


        $deleted = DB::table('VEICULOS')->where('placa', $placa)->delete();

        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Veículo excluído com sucesso!'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir veículo ou veículo não encontrado'], 400);
        }
    }
    public function editVeiculos(Request $request)
    {
        // dd($request->all());
        $veiculo = DB::table('VEICULOS')->where('cod_veic', $request->numVeiculo)->first();

        if (!$veiculo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Veículo não encontrado',
            ], 404);
        }

        DB::table('VEICULOS')
            ->where('cod_veic', $request->numVeiculo)
            ->update([
                'modelo' => $request->modelo,
                'ano' => $request->ano,
                'capacidade' => $request->capacidade,
                'placa' => $request->placa,
                'dt_prox_manu' => $request->dataProxManutencao,
                'dt_ultim_manu' => $request->dataUltManutencao,
                'empresa' => $request->empresa,
                'motorista' => $request->motorista,
                'tipo_veiculo' => $request->tipoVeiculo,
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Veículo atualizado com sucesso',
        ], 200);
    }

    public function editStatusVeiculo(Request $request)
    {
        // dd($request->all());
        $veiculo = DB::table('VEICULOS')->where('placa', $request->placa)->first();

        if (!$veiculo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Veículo não encontrado',
            ], 404);
        }

        DB::table('VEICULOS')
            ->where('placa', $request->placa)
            ->update([
                'status' => $request->status,
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status do veículo atualizado com sucesso',
        ], 200);
    }

    public function getMotoristas(Request $request)
    {

        $motoristas = DB::table('usuarios as u')
            ->select(
                'u.cod_usur',
                'u.nome',
            )
            ->where('u.tipo', 'Motorista')
            ->get();

        return response($motoristas, 200);
    }
}