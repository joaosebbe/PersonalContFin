<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function auth(Request $request){
        //Pegando o usuario do banco manualmente
        $user = User::where('email', $request->input('email'))->first();

        if(!$user){
            return redirect()->route('login')->withErrors(['error' => 'usuario inexistente']);
        }
        if($request->input('senha') != $user->password){
            return redirect()->route('login')->withErrors(['error' => 'Email ou senha inválidos! Tente novamente']);
        }

        //faz o login automatico pelo id
        Auth::loginUsingId($user->id);

        session()->put('dataAnoMes', date('Y-m'));

        // $despesas = ContasController::retornaDespesas($user->id);

        return redirect('/inicio');
        // return dd($despesas);
        
    }

    public function destroy(){

        Auth::logout();

        return redirect()->route('login');

    }
}
