<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function insertUsuario(Request $request)
    {
        // dd($request);
        $lastCodUsur = DB::table('USUARIOS')->max('cod_usuario');
        $newCodUsur = $lastCodUsur + 1;

        $usuario = DB::table('USUARIOS')->insert([
            'cod_usuario' => $newCodUsur, 
            'nome' => $request->input('nome'),
            'cpf' => $request->input('cpf'),
            'email' => $request->input('email'),
            'status' => $request->input('status'),
            'cod_tipo_usuario' => $request->input('tipoUsuario'),
            'dt_cadastro_usur' => now(),
            'senha' => '123456', 
        ]);

        switch ($request->input('tipoUsuario')) {
            case 1:
                $tipoUsuario = 'Funcionario';
                break;
            case 2:
                $tipoUsuario = 'Terceiro';
                break;
            case 3:
                $tipoUsuario = 'Motorista';
                break;
            default:
                $tipoUsuario = null;
                break;
        }

        if ($tipoUsuario) {
            DB::table('tipos_usuario')->insert([
                'cod_usuario' => $newCodUsur,
                'cod_tipo_usuario' => $request->input('tipoUsuario'),
                'descricao' => $tipoUsuario,
            ]);
        } else {
            return response()->json(['error' => 'Tipo de usuário inválido.'], 400);
        }
        return response()->json(['message' => 'Usuário salvo com sucesso!'], 201);
    }


    public function getUsuarios(Request $request)
{
    $query = DB::table('usuarios as usur')
        ->Leftjoin('tipos_usuario as tipos', 'usur.cod_usuario', '=', 'tipos.cod_usuario')
        ->select(
            'usur.cod_usuario',
            'usur.nome',
            'usur.cpf',
            'usur.email',
            'usur.status',
            'tipos.descricao'
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
    $codUsur = $request->input('codUsur'); 

    if (!$codUsur) {
        return response()->json(['success' => false, 'message' => 'Código do usuário não fornecido'], 400);
    }

  
    $deleted = DB::table('usuarios')->where('cod_usuario', $codUsur)->delete();
    
    $deletedTipo = DB::table('tipos_usuario')->where('cod_usuario', $codUsur)->delete();

    if ($deleted && $deletedTipo) {
        return response()->json(['success' => true, 'message' => 'Usuário excluído com sucesso!'], 200);
    } else {
        return response()->json(['success' => false, 'message' => 'Erro ao excluir usuário ou usuário não encontrado'], 400);
    }
}
public function editUsuarios(Request $request)
{
    $usuario = DB::table('usuarios')->where('cod_usuario', $request->codUsur)->first();
    // dd($request);
    if (!$usuario) {
        return response()->json([
            'status' => 'error',
            'message' => 'Usuário não encontrado',
        ], 404);
    }

    switch ($request->tipo) {
        case 1:
            $tipoUsuario = 'Funcionario';
            break;
        case 2:
            $tipoUsuario = 'Terceiro';
            break;
        case 3:
            $tipoUsuario = 'Motorista';
            break;
        default:
            $tipoUsuario = null;
            break;
    }

    DB::table('usuarios as usur')
    ->leftJoin('tipos_usuario as tipos', 'usur.cod_usuario', '=', 'tipos.cod_usuario')
    ->where('usur.cod_usuario', $request->codUsur)  
    ->update([
        'usur.nome' => $request->nome,
        'usur.cpf' => $request->cpf,
        'usur.email' => $request->email,
        'usur.status' => $request->status,
        'tipos.cod_tipo_usuario' => $request->tipo,
        'tipos.descricao' => $tipoUsuario,
    ]);


    return response()->json([
        'status' => 'success',
        'message' => 'Usuário atualizado com sucesso',
    ], 200);
}
}
