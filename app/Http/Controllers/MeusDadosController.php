<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MeusDadosController extends Controller
{
    public function editaDados(Request $request)
    {
        $telefoneSemMascara = MeusDadosController::retiraMascaraTelefone($request->celularEdit);

        DB::table('users')
            ->where('id', auth()->user()->id)
            ->limit(1)
            ->update(array(
                'name' => $request->nomeEdit,
                'email' => $request->emailEdit,
                'telefone' => $telefoneSemMascara
            ));

        return redirect('/meusdados');
    }

    public function alteraSenha(Request $request)
    {
        $senhaNova = Hash::make($request->senhaNovaConfirma);

        DB::table('users')
            ->where('id', auth()->user()->id)
            ->limit(1)
            ->update(array(
                'password' => $senhaNova
            ));

        Auth::logout();

        return redirect()->route('login');
    }

    public static function verificaSenha($password, $senhaBanco = null)
    {
        $result = 'false';
        $senhaBcrypt = $senhaBanco;

        if ($senhaBanco == null) {
            $senhaBcrypt = auth()->user()->password;
        }

        if (Hash::check($password, $senhaBcrypt)) {
            $result = 'true';
        }

        return response()->json([
            'senhaExiste' => $result,
            'cript' => $senhaBcrypt
        ]);
    }

    public static function retiraMascaraTelefone($telefone)
    {
        $result = str_replace(' ', '', $telefone);
        $result = str_replace('(', '', $result);
        $result = str_replace(')', '', $result);
        $result = str_replace('-', '', $result);

        return $result;
    }
}
