<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function insertUsuario(Request $request)
    {
        $usuario = DB::table('USUARIOS')->insert([
            'nome' => $request->input('nome'),
            'cpf' => $request->input('cpf'),
            'email' => $request->input('email'),
            'status' => $request->input('status'),
            'tipo' => $request->input('tipoUsuario'),
        ]);
    
        return response()->json(['success' => true, 'message' => 'Usuario cadastrado com sucesso!'], 200);
    }

    public function getUsuarios(Request $request)
    {
    
        $usuarios = DB::table('usuarios as usur')
            ->select(
                'usur.nome',
                'usur.cpf',
                'usur.email',
                'usur.status',
                'usur.tipo',
            )
            ->get();
    
        return response($usuarios, 200);  
    }

    public function deleteUsuarios(Request $request)
{   
    // dd($request);
    $cpf = $request->input('cpf'); 

    if (!$cpf) {
        return response()->json(['success' => false, 'message' => 'CPF do usuário não fornecido'], 400);
    }

  
    $deleted = DB::table('usuarios')->where('cpf', $cpf)->delete();

    if ($deleted) {
        return response()->json(['success' => true, 'message' => 'Usuário excluído com sucesso!'], 200);
    } else {
        return response()->json(['success' => false, 'message' => 'Erro ao excluir usuário ou usuário não encontrado'], 400);
    }
}
public function editUsuarios(Request $request)
{
    $usuario = DB::table('usuarios')->where('cpf', $request->cpf)->first();
    // dd($request);
    if (!$usuario) {
        return response()->json([
            'status' => 'error',
            'message' => 'Usuário não encontrado',
        ], 404);
    }

    DB::table('usuarios')
        ->where('cpf', $request->cpf)
        ->update([
            'nome' => $request->nome,
            'cpf' => $request->cpf,
            'email' => $request->email,
            'status' => $request->status,
            'tipo' => $request->tipo,
        ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Usuário atualizado com sucesso',
    ], 200);
}
}
