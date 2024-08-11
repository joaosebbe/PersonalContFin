<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceitaController extends Controller
{
    
    public function buscaReceitaAtual(){
        $receita = DB::select("select * from users where id = '" . auth()->user()->id . "'");
        return $receita[0]->valor_receita;
    }

    public function verificaReceita(){
        $anoMes = session()->get('dataAnoMes');

        $buscaReceitaMesAtual = DB::select("SELECT * FROM tab_logalteracoes WHERE tipo_alteracao = 'RECEITA' AND id_user = '" . auth()->user()->id . "' AND (data_alteracao = '" . date("Ym", strtotime($anoMes)) . "' OR data_alteracao < '" . date("Ym", strtotime($anoMes)) . "') ORDER BY data_alteracao DESC LIMIT 1");
        
        $valReceita = '';
        if(count($buscaReceitaMesAtual) == 1){

            $valReceita = $buscaReceitaMesAtual[0]->valor_novo;

        }else if(count($buscaReceitaMesAtual) == 0){

            $buscaPrimeiraReceita = DB::select("SELECT * FROM tab_logalteracoes WHERE tipo_alteracao = 'RECEITA' AND id_user = '" . auth()->user()->id . "' ORDER BY data_alteracao ASC LIMIT 1");
            if(count($buscaPrimeiraReceita) == 1){
                $valReceita = $buscaPrimeiraReceita[0]->valor_antigo;
            }else if(count($buscaPrimeiraReceita) == 0){
                $valReceita = auth()->user()->valor_receita;
            }
        }

        return $valReceita;
    }

    public function alterarReceita(Request $request)
    {
        $valorAtual = floatval(ReceitaController::buscaReceitaAtual());
        $novoValor = ContasController::retiraMascaraDinheiro($request->receita);
        if($valorAtual != $novoValor){
            DB::table('users')
            ->where('id', auth()->user()->id)
            ->limit(1)
            ->update(array(
                'valor_receita' => $novoValor,
            ));
        
            ReceitaController::inserirAlteracaoLog($valorAtual, $novoValor);
        }
        
        return redirect('/inicio');
    }

    public function inserirAlteracaoLog($valorAntigo, $valorNovo){
        //faz a verificacao se ja tem alguma alteração no mes vigente e caso tenha ele sobrescreve, se não cria outro log
        $verificaData = DB::select("select * from tab_logalteracoes where id_user = '" . auth()->user()->id . "' AND data_alteracao LIKE '" . date("Ym",strtotime(session()->get('dataAnoMes'))) . "%' ");
        if(count($verificaData) == 0){
            DB::table('tab_logalteracoes')->insert([
                'id_user' => auth()->user()->id,
                'tipo_alteracao' => 'RECEITA',
                'valor_antigo' => $valorAntigo,
                'valor_novo' => $valorNovo,
                'data_alteracao' => date("Ym",strtotime(session()->get('dataAnoMes')))
            ]);
        }else{
            DB::table('tab_logalteracoes')
            ->where('id_alteracao', $verificaData[0]->id_alteracao)
            ->limit(1)
            ->update(array(
                'valor_antigo' => $verificaData[0]->valor_antigo,
                'valor_novo' => $valorNovo
            ));
        }
        
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
