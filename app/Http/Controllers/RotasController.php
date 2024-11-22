<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RotasController extends Controller
{
    public function buscarEndereco($cep)
    {
        $response = Http::withoutVerifying()->get("https://viacep.com.br/ws/{$cep}/json/");
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'CEP inválido ou erro na requisição'], 400);
        }
    }

    public function insertRotas(Request $request)
    {
        $placa = $request->input('veiculo.placa');

        $codRota = DB::table('ROTAS')
            ->where('PLACA', $placa)
            ->count() + 1;

        DB::table('ROTAS')->insert([
            'COD_ROTA' => $codRota,
            'CEP_PARTIDA' => $request->input('cepPartida'),
            'CEP_CHEGADA' => $request->input('cepChegada'),
            'NUMERO_PARTIDA' => $request->input('numeroPartida'),
            'NUMERO_CHEGADA' => $request->input('numeroChegada'),
            'DESCRICAO_PARTIDA' => $request->input('descricaoPartida'),
            'DESCRICAO_CHEGADA' => $request->input('descricaoChegada'),
            'COMPLEMENTO_PARTIDA' => $request->input('complementoPartida'),
            'COMPLEMENTO_CHEGADA' => $request->input('complementoChegada'),
            'RUA_PARTIDA' => $request->input('enderecoPartida.rua'),
            'BAIRRO_PARTIDA' => $request->input('enderecoPartida.bairro'),
            'CIDADE_PARTIDA' => $request->input('enderecoPartida.cidade'),
            'ESTADO_PARTIDA' => $request->input('enderecoPartida.estado'),
            'RUA_CHEGADA' => $request->input('enderecoChegada.rua'),
            'BAIRRO_CHEGADA' => $request->input('enderecoChegada.bairro'),
            'CIDADE_CHEGADA' => $request->input('enderecoChegada.cidade'),
            'ESTADO_CHEGADA' => $request->input('enderecoChegada.estado'),
            'DATA_HORA_INICIO' => now(),
            'DATA_HORA_CHEGADA' => now()->addHours(2),
            'PLACA' => $placa,
        ]);

        return response()->json(['success' => true, 'message' => 'Rota cadastrada com sucesso!'], 200);
    }


    public function getRotas(Request $request)
    {
        $placa = $request->query('placa');
        $rotas = DB::table('rotas as r')
            ->join('veiculos as v', 'v.placa', '=', 'r.placa')
            ->select(
                'r.ID',
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
                'r.status',
                'r.DESCRICAO_ROTA',
                'r.COD_ROTA',
                'r.DESVIOS',
                'r.PARADAS',
                'r.ROTA_ALTERNATIVA',
                'r.INCIDENTES',
            )
            ->where('v.placa', $placa)
            ->get();


        return response($rotas, 200);
    }
    
    public function getObsRotas(Request $request)
    {
        $codRota = $request->query('codRota');
        $placa = $request->query('placa');
        $rotas = DB::table('rotas as r')
            ->join('veiculos as v', 'v.placa', '=', 'r.placa')
            ->select(
                'r.DESVIOS',
                'r.PARADAS',
                'r.ROTA_ALTERNATIVA',
                'r.INCIDENTES',
            )
            ->where('v.placa', $placa)
            ->where('r.COD_ROTA', $codRota)
            ->get();

        return response($rotas, 200);
    }
    public function editStatusRota(Request $request)
    {
        $rota = DB::table('ROTAS')->where('placa', $request->placa)->first();

        if (!$rota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rota não encontrada',
            ], 404);
        }

        DB::table('ROTAS')
            ->where('placa', $request->placa)
            ->update([
                'status' => $request->status,
                'DESCRICAO_ROTA' => $request->desc
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status da rota atualizado com sucesso',
        ], 200);
    }
    public function updateObsRotas(Request $request)
    {
        $placa = $request->input('placa'); 

        $rota = DB::table('ROTAS')->where('PLACA', $placa)->first();

        if (!$rota) {
            return response()->json(['success' => false, 'message' => 'Rota não encontrada para essa placa.'], 404);
        }

        DB::table('ROTAS')
            ->where('PLACA', $placa)
            ->update([
                'DESVIOS' => $request->input('desvios'),
                'PARADAS' => $request->input('paradas'),
                'INCIDENTES' => $request->input('incidentes'),
                'ROTA_ALTERNATIVA' => $request->input('rotaAlternativa'),
            ]);

        return response()->json(['success' => true, 'message' => 'Observações atualizadas com sucesso!'], 200);
    }
}