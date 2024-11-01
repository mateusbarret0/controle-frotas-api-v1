<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; 
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'senha' => 'required|string',
        ]);
    
        $usuario = DB::table('USUARIOS')->where('NOME', $request->usuario)->first();
    
        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }
    
        if ($usuario && $request->senha === $usuario->senha) {
            return response()->json(['message' => 'Login bem-sucedido!'], 200);
        }
    
        return response()->json(['message' => 'Usuário ou senha incorretos.'], 401);
    }

}
