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
                'veic.manutencao',
                )
                ->get();

            return response($veiculos, 200);  
    }
    
    public function insertVeiculos(Request $request)
    {
            // $dados = $request->query();
        
            $veiculos = DB::table('VEICULOS as veic')
                ->select(
                'veic.modelo',
                'veic.placa',
                'veic.capacidade',
                'veic.manutencao',
                )
                ->get();

            return response($veiculos, 200);  
    }
}
