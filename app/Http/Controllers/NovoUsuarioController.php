<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NovoUsuarioController extends Controller
{
    public function insereNovoUsuario(Request $request){
        $senhaPadrao = Hash::make(env('SENHA_PADRAO'));
        $telefone = MeusDadosController::retiraMascaraTelefone($request->celularUsuario);

        DB::table('users')->insert([
            'name' => $request->nomeUsuario,
            'email' => $request->emailUsuario,
            'telefone' => $telefone,
            'password' => $senhaPadrao
        ]);

        return redirect('/meusdados');
    }

    public function buscaUsuarios(){
        $usuarios = DB::select('select name, email, telefone from users');

        return response()->json([
            'users' => $usuarios
        ]);
    }

    public function verificaInfoExistentes($email, $telefone){
        $telefoneSemMask = MeusDadosController::retiraMascaraTelefone($telefone);

        $usuarios = DB::select('select * from users where email = "' . $email . '" or telefone = "' . $telefoneSemMask . '"');

        $infoExiste = 'false';

        if(count($usuarios) > 0){
            $infoExiste = 'true';
        }

        return response()->json([
            'infoExiste' => $infoExiste
        ]);
    }
}
