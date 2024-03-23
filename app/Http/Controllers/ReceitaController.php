<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceitaController extends Controller
{
    public function alterarReceita(Request $request)
    {
        $receita = ContasController::retiraMascaraDinheiro($request->receita);
        DB::table('users')
            ->where('id', auth()->user()->id)
            ->limit(1)
            ->update(array(
                'valor_receita' => $receita,
            ));
        // return dd($receita);
        return redirect('/contas');
    }

    public function insereReceitaUnica(Request $request)
    {
        $receita = ContasController::retiraMascaraDinheiro($request->receitaUnica);

        DB::table('tab_despesas')->insert([
            'usuario' => auth()->user()->id,
            'descricao' => $request->descRecUnica,
            'valor' => $receita,
            'data_cobranca' => date('Y-m-d'),
            'data_inicio' => date('Y-m-d'),
            'receita_despesa' => 'R',
        ]);

        return redirect('/contas');
    }
}
