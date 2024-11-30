<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function insertUsuario(Request $request)
{
    $lastCodUsur = DB::table('USUARIOS')->max('cod_usur');
    $newCodUsur = $lastCodUsur + 1;

    $usuario = DB::table('USUARIOS')->insert([
        'cod_usur' => $newCodUsur, 
        'nome' => $request->input('nome'),
        'cpf' => $request->input('cpf'),
        'email' => $request->input('email'),
        'status' => $request->input('status'),
        'tipo' => $request->input('tipoUsuario'),
        'dt_cadastro' => now(),
        'senha' => '123456',
    ]);

    return response()->json(['success' => true, 'message' => 'Usuário cadastrado com sucesso!'], 200);
}

    public function getUsuarios(Request $request)
{
    $query = DB::table('usuarios as usur')
        ->select(
            'usur.cod_usur',
            'usur.nome',
            'usur.cpf',
            'usur.email',
            'usur.status',
            'usur.tipo'
        );

    if ($request->has('search') && $request->input('search') != '') {
        $searchTerm = $request->input('search');

        $query->where(function($subQuery) use ($searchTerm) {
            $subQuery->where('usur.nome', 'LIKE', '%' . $searchTerm . '%')
                     ->orWhere('usur.email', 'LIKE', '%' . $searchTerm . '%')
                     ->orWhere('usur.cpf', 'LIKE', '%' . $searchTerm . '%');
        });
    }

    $usuarios = $query->get();

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
    $usuario = DB::table('usuarios')->where('cod_usur', $request->codUsur)->first();
    // dd($request);
    if (!$usuario) {
        return response()->json([
            'status' => 'error',
            'message' => 'Usuário não encontrado',
        ], 404);
    }

    DB::table('usuarios')
        ->where('cod_usur', $request->codUsur)
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
