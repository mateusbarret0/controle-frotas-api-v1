<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RotasController extends Controller
{
    public function buscarEndereco($cep)
    {
        // dd($cep);
        $response = Http::withoutVerifying()->get("https://viacep.com.br/ws/{$cep}/json/");
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'CEP inválido ou erro na requisição'], 400);
        }
    }

    public function insertRotas(Request $request)
    {
        $cod_veiculo = $request->input('veiculo.cod_veiculo');

        $codRota = DB::table('ROTAS')
            ->where('cod_veiculo', $cod_veiculo)
            ->count() + 1;

        DB::table('ROTAS')->insert([
            'COD_ROTA' => $codRota,
            'cod_veiculo' => $cod_veiculo,
            'cod_parada' => 1,
            'cod_chegada' => $codRota,
            'cod_partida' => $codRota,
        ]);

        DB::table('PARTIDAS')->insert([
            'cod_rota' => $codRota,
            'cod_partida' => $codRota,
            'cep_partida' => $request->input('cepPartida'),
            'numero_partida' => $request->input('numeroPartida'),
            'descricao_partida' => $request->input('descricaoPartida'),
            'complemento_partida' => $request->input('complementoPartida'),
            'rua_partida' => $request->input('enderecoPartida.rua'),
            'bairro_partida' => $request->input('enderecoPartida.bairro'),
            'cidade_partida' => $request->input('enderecoPartida.cidade'),
            'estado_partida' => $request->input('enderecoPartida.estado'),
            'data_hora_partida' => now(),
        ]);


        DB::table('CHEGADAS')->insert([
            'cod_rota' => $codRota,
            'cod_chegada' => $codRota,
            'cep_chegada' => $request->input('cepChegada'),
            'numero_chegada' => $request->input('numeroChegada'),
            'descricao_chegada' => $request->input('descricaoChegada'),
            'complemento_chegada' => $request->input('complementoChegada'),
            'rua_chegada' => $request->input('enderecoChegada.rua'),
            'bairro_chegada' => $request->input('enderecoChegada.bairro'),
            'cidade_chegada' => $request->input('enderecoChegada.cidade'),
            'estado_chegada' => $request->input('enderecoChegada.estado'),
            'data_hora_chegada' => now()->addHours(2),
        ]);

        $paradas = $request->input('paradas');
        if (!empty($paradas)) {
            $paradasData = [];
            $codParada = 1;

            foreach ($paradas as $parada) {
                $paradasData[] = [
                    'COD_ROTA' => $codRota,
                    'COD_PARADA' => $codParada,
                    'CEP_PARADA' => $parada['cep'],
                    'NUMERO_PARADA' => $parada['numero'],
                    'DESCRICAO_PARADA' => $parada['descricao'] ?? null,
                    'COMPLEMENTO_PARADA' => $parada['complemento'] ?? null,
                    'RUA_PARADA' => $parada['endereco']['rua'],
                    'BAIRRO_PARADA' => $parada['endereco']['bairro'],
                    'CIDADE_PARADA' => $parada['endereco']['cidade'],
                    'ESTADO_PARADA' => $parada['endereco']['estado'],
                ];
                $codParada++;
            }

            DB::table('PARADAS')->insert($paradasData);
        }

        return response()->json(['success' => true, 'message' => 'Rota e paradas cadastradas com sucesso!'], 200);
    }


    public function getRotas(Request $request)
    {
        // dd($request->all());
        $cod_veiculo = $request->query('cod_veiculo');

        $rotasRaw = DB::table('ROTAS as r')
            ->join('VEICULOS as v', 'r.cod_veiculo', '=', 'v.cod_veiculo')
            ->join('PARTIDAS as p', 'r.cod_partida', '=', 'p.cod_partida')
            ->join('CHEGADAS as c', 'r.cod_chegada', '=', 'c.cod_chegada')
            ->leftjoin('PARADAS as pr', 'r.cod_rota', '=', 'pr.cod_rota')
            ->select(
                'r.cod_rota',
                'r.cod_veiculo',
                'r.status',
                'r.desc_status',
                'p.cep_partida',
                'p.numero_partida',
                'p.descricao_partida',
                'p.complemento_partida',
                'p.rua_partida',
                'p.bairro_partida',
                'p.cidade_partida',
                'p.estado_partida',
                'p.data_hora_partida',
                'c.cep_chegada',
                'c.numero_chegada',
                'c.descricao_chegada',
                'c.complemento_chegada',
                'c.rua_chegada',
                'c.bairro_chegada',
                'c.cidade_chegada',
                'c.estado_chegada',
                'c.data_hora_chegada',
                'pr.cod_parada',
                'pr.cep_parada as cepParada',
                'pr.numero_parada as numeroParada',
                'pr.descricao_parada as descricaoParada',
                'pr.complemento_parada as complementoParada',
                'pr.rua_parada as ruaParada',
                'pr.bairro_parada as bairroParada',
                'pr.cidade_parada as cidadeParada',
                'pr.estado_parada as estadoParada'
            )
            ->when($cod_veiculo, function ($query, $cod_veiculo) {
                $query->where('v.cod_veiculo', $cod_veiculo);
            })
            ->get();

        $rotas = [];
        foreach ($rotasRaw as $row) {
            $codRota = $row->cod_rota;

            if (!isset($rotas[$codRota])) {
                $rotas[$codRota] = [
                    'cod_rota' => $row->cod_rota,
                    'cod_veiculo' => $row->cod_veiculo,
                    'status' => $row->status,
                    'desc_status' => $row->desc_status,
                    'partida' => [
                        'cep' => $row->cep_partida,
                        'numero' => $row->numero_partida,
                        'descricao' => $row->descricao_partida,
                        'complemento' => $row->complemento_partida,
                        'rua' => $row->rua_partida,
                        'bairro' => $row->bairro_partida,
                        'cidade' => $row->cidade_partida,
                        'estado' => $row->estado_partida,
                        'data_hora' => $row->data_hora_partida,
                    ],
                    'chegada' => [
                        'cep' => $row->cep_chegada,
                        'numero' => $row->numero_chegada,
                        'descricao' => $row->descricao_chegada,
                        'complemento' => $row->complemento_chegada,
                        'rua' => $row->rua_chegada,
                        'bairro' => $row->bairro_chegada,
                        'cidade' => $row->cidade_chegada,
                        'estado' => $row->estado_chegada,
                        'data_hora' => $row->data_hora_chegada,
                    ],
                    'paradas' => [],
                ];
            }

            if ($row->cod_parada) {
                $rotas[$codRota]['paradas'][] = [
                    'cod_parada' => $row->cod_parada,
                    'cep' => $row->cepParada,
                    'numero' => $row->numeroParada,
                    'descricao' => $row->descricaoParada,
                    'complemento' => $row->complementoParada,
                    'rua' => $row->ruaParada,
                    'bairro' => $row->bairroParada,
                    'cidade' => $row->cidadeParada,
                    'estado' => $row->estadoParada,
                ];
            }
        }

        $rotas = array_values($rotas);

        return response()->json($rotas, 200);
    }


    public function getObsRotas(Request $request)
    {
        // dd($request->all());
        $cod_rota = $request->query('cod_rota');
        $cod_veiculo = $request->query('cod_veiculo');
        $rotas = DB::table('rotas')
            ->select(
                'desvios',
                'paradas',
                'rota_alternativa',
                'incidentes',
            )
            ->where('cod_veiculo', $cod_veiculo)
            ->where('cod_rota', $cod_rota)
            ->get();

        return response($rotas, 200);
    }
    public function getStatusRotas(Request $request)
    {
        // dd($request->all());
        $cod_rota = $request->query('cod_rota');
        $cod_veiculo = $request->query('cod_veiculo');
        $rotas = DB::table('rotas')
            ->select(
                'status',
                'desc_status',
            )
            ->where('cod_veiculo', $cod_veiculo)
            ->where('cod_rota', $cod_rota)
            ->get();

        return response($rotas, 200);
    }
    public function editStatusRota(Request $request)
    {
        // dd($request->all());
        $codRota = $request->input('codRota');
        $rota = DB::table('ROTAS')->where('cod_veiculo', $request->cod_veiculo)->first();

        if (!$rota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rota não encontrada',
            ], 404);
        }

        DB::table('ROTAS')
            ->where('cod_veiculo', $request->cod_veiculo)
            ->where('cod_rota', $codRota)
            ->update([
                'status' => $request->status,
                'desc_status' => $request->desc
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status da rota atualizado com sucesso',
        ], 200);
    }
    public function updateObsRotas(Request $request)
    {
        $cod_veiculo = $request->input('cod_veiculo');
        $cod_rota = $request->input('cod_rota');
        $rota = DB::table('ROTAS')->where('cod_veiculo', $cod_veiculo)->first();

        if (!$rota) {
            return response()->json(['success' => false, 'message' => `Rota não encontrada para o código de rota $cod_rota `], 404);
        }

        DB::table('ROTAS')
            ->where('cod_veiculo', $cod_veiculo)
            ->where('cod_rota', $cod_rota)
            ->update([
                'DESVIOS' => $request->input('desvios'),
                'PARADAS' => $request->input('paradas'),
                'INCIDENTES' => $request->input('incidentes'),
                'ROTA_ALTERNATIVA' => $request->input('rotaAlternativa'),
            ]);

        return response()->json(['success' => true, 'message' => 'Observações atualizadas com sucesso!'], 200);
    }
}