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
}
