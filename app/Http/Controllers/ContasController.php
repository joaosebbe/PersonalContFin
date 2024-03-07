<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ContasController extends Controller
{
    public function alterarReceita(Request $request)
    {
        $receita = trim(str_replace('R$', '', $request->receita));
        $receita = trim(str_replace('.', '', $receita));
        $receita = floatval(trim(str_replace(',', '.', $receita)));
        DB::table('users')
            ->where('id', auth()->user()->id)
            ->limit(1)
            ->update(array(
                'valor_receita' => $receita,
            ));
        // return dd($receita);
        return view('inicio');
    }
}
