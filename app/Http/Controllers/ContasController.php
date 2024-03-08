<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ContasController extends Controller
{
    public function carregaSelects()
    {
        $anoMesAtual = date('Y-m');
        $tiposDespesas = DB::select('select * from tab_tiposgastos where id_usuario = ?', [auth()->user()->id]);
        $atrelamentos = DB::select('select * from tab_atrelamento where id_usuario = ?', [auth()->user()->id]);
        $despesas = DB::table('tab_despesas')
            ->leftJoin('tab_tiposgastos', 'tab_despesas.tipo_gasto', '=', 'tab_tiposgastos.id_tipo')
            ->leftJoin('tab_atrelamento', 'tab_despesas.atrelamento', '=', 'tab_atrelamento.id_atrelamento')
            ->where('tab_despesas.usuario', '=', auth()->user()->id)
            ->select(
                'tab_despesas.*',
                'tab_tiposgastos.*',
                'tab_atrelamento.*'
            )
            ->get();

        return view('contas', compact('tiposDespesas', 'atrelamentos', 'despesas'));
    }

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

    public function insereTipoDespesa(Request $request)
    {
        DB::table('tab_tiposgastos')->insert([
            'id_usuario' => auth()->user()->id,
            'nome_gasto' => $request->tipoDespesaCad,
        ]);

        return redirect('/contas');
    }

    public function insereAtrelamento(Request $request)
    {
        DB::table('tab_atrelamento')->insert([
            'nome_atrelamento' => $request->tipoAtrelamento,
            'id_usuario' => auth()->user()->id,
        ]);

        return redirect('/contas');
    }

    public function insereDespesa(Request $request)
    {
        $despesa = ContasController::retiraMascaraDinheiro($request->valorDespesa);

        if ($request->opcaoPagamento == 'CREDITO') {
            $dataInicio = Carbon::parse($request->dataInicio);
            $dataFim = Carbon::parse($request->dataFim);

            // Calcula a diferença entre as datas
            $diferenca = $dataInicio->diffInMonths($dataFim);

            $dataFim = $request->dataFim;
            if(date('Y-m', strtotime($request->dataInicio)) > date('Y-m', strtotime($dataFim))){
                $dataFim = $request->dataInicio;
            }
            if(date('Y-m', strtotime($request->dataInicio)) == date('Y-m', strtotime($dataFim))){
                $diferenca = 1;
            }
            $valorMensal = $despesa / $diferenca;
            $dataCobranca = date('Y-m-d', strtotime($request->dataInicio));

            for($i = 0; $i < $diferenca; $i++){
                $dataCobranca = date('Y-m-d', strtotime($request->dataInicio . ' +' . $i . ' month'));
                DB::table('tab_despesas')->insert([
                    'usuario' => auth()->user()->id,
                    'descricao' => $request->nomeDespesa,
                    'valor' => $despesa,
                    'tipo_gasto' => $request->tipoDespesa,
                    'data_cobranca' => $dataCobranca,
                    'data_inicio' => date('Y-m-d', strtotime($request->dataInicio)),
                    'data_fim' => (($dataFim != '') ? date('Y-m-d', strtotime($dataFim)) : date('Y-m-d')),
                    'tipo_pagamento' => $request->opcaoPagamento,
                    'atrelamento' => $request->atrelamento,
                    'nmr_parcelas' => $diferenca,
                    'parcela' => $i + 1,
                    'valor_quebrado' => $valorMensal,
                ]);
            }
        } else {
            DB::table('tab_despesas')->insert([
                'usuario' => auth()->user()->id,
                'descricao' => $request->nomeDespesa,
                'valor' => $despesa,
                'tipo_gasto' => $request->tipoDespesa,
                'data_cobranca' => date('Y-m-d', strtotime($request->dataInicio)),
                'data_inicio' => date('Y-m-d', strtotime($request->dataInicio)),
                'tipo_pagamento' => $request->opcaoPagamento,
                'despesa_fixa' => (($request->contaFixa == 'on') ? 'S' : 'N'),
                'atrelamento' => $request->atrelamento,
            ]);
        }

        return redirect('/contas');
    }

    public static function retiraMascaraDinheiro($valor)
    {
        $novoValor = trim(str_replace('R$', '', $valor));
        $novoValor = trim(str_replace('.', '', $novoValor));
        $novoValor = floatval(trim(str_replace(',', '.', $novoValor)));

        return $novoValor;
    }
}
