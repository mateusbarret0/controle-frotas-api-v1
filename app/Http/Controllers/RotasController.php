<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RotasController extends Controller
{
    public function buscarEndereco($cep)
    {
        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'CEP inválido ou erro na requisição'], 400);
        }
    }
}
