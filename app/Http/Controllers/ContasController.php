<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ContasController extends Controller
{
    public function retornaDespesas()
    {
        $anoMes = session()->get('dataAnoMes');
        $despesas = DB::select("
        SELECT SUM(CASE WHEN p.valor_quebrado IS NOT NULL THEN p.valor_quebrado ELSE d.valor END) AS total_valor, t.nome_gasto
        FROM tab_despesas d 
        LEFT JOIN tab_parcelas p ON d.id_despesa = p.id_despesa
        LEFT JOIN tab_tiposgastos t ON t.id_tipo = d.tipo_gasto
        WHERE d.receita_despesa = 'D' AND d.usuario = '" . auth()->user()->id . "' AND ((CASE WHEN p.data IS NOT NULL THEN p.data like '$anoMes%' ELSE d.data_cobranca like '$anoMes%' END) OR d.despesa_fixa = 'S') GROUP BY d.tipo_gasto, t.nome_gasto;
        ");

        // return dd($despesas);
        return view('inicio', compact('despesas'));
    }

    public function carregaSelects()
    {
        $anoMes = session()->get('dataAnoMes');
        $tiposDespesas = DB::select('select * from tab_tiposgastos where id_usuario = ?', [auth()->user()->id]);
        $atrelamentos = DB::select('select * from tab_atrelamento where id_usuario = ?', [auth()->user()->id]);
        $despesas = DB::select("SELECT d.*, t.*, a.*, p.data, p.parcela_atual, p.valor_quebrado 
        FROM tab_despesas d 
        LEFT JOIN tab_tiposgastos t ON t.id_tipo = d.tipo_gasto
        LEFT JOIN tab_atrelamento a ON a.id_atrelamento = d.atrelamento
        LEFT JOIN tab_parcelas p ON p.id_despesa = d.id_despesa
        WHERE d.usuario = '" . auth()->user()->id . "' AND ((CASE WHEN p.data IS NOT NULL THEN p.data like '$anoMes%' ELSE d.data_cobranca like '$anoMes%' END) OR d.despesa_fixa = 'S')");

        return view('contas', compact('tiposDespesas', 'atrelamentos', 'despesas'));
    }

    public function carregaChecklist()
    {
        $anoMes = session()->get('dataAnoMes');

        $despCredito = DB::select("
        SELECT SUM(CASE WHEN p.valor_quebrado IS NOT NULL THEN p.valor_quebrado ELSE d.valor END) AS total_valor,d.id_despesa, a.nome_atrelamento, d.descricao FROM tab_despesas d LEFT JOIN tab_tiposgastos t ON t.id_tipo = d.tipo_gasto LEFT JOIN tab_atrelamento a ON a.id_atrelamento = d.atrelamento LEFT JOIN tab_parcelas p ON p.id_despesa = d.id_despesa WHERE d.usuario = '" . auth()->user()->id . "' AND d.tipo_pagamento = 'CREDITO' AND (CASE WHEN p.data IS NOT NULL THEN p.data like '$anoMes%' ELSE d.data_cobranca like '$anoMes%' END) GROUP BY d.atrelamento, d.id_despesa, a.nome_atrelamento, d.descricao
        ");

        $despFixa = DB::select("SELECT * FROM tab_despesas WHERE despesa_fixa = 'S'");

        // return dd($despCredito);
        return view('checklist', compact('despCredito','despFixa'));
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
            if (date('Y-m', strtotime($request->dataInicio)) > date('Y-m', strtotime($dataFim))) {
                $dataFim = $request->dataInicio;
            }
            if (date('Y-m', strtotime($request->dataInicio)) == date('Y-m', strtotime($dataFim))) {
                $diferenca = 1;
            }
            $valorMensal = $despesa / $diferenca;
            $dataCobranca = date('Y-m-d', strtotime($request->dataInicio));

            $idDespesa = DB::table('tab_despesas')->insertGetId([
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
            ]);

            for ($i = 0; $i < $diferenca; $i++) {
                $dataCobranca = date('Y-m-d', strtotime($request->dataInicio . ' +' . $i . ' month'));
                DB::table('tab_parcelas')->insert([
                    'id_despesa' => $idDespesa,
                    'data' => $dataCobranca,
                    'parcela_atual' => $i + 1,
                    'valor_quebrado' => $valorMensal,
                ]);
            }
        } else {
            $data = date('Y-m-d', strtotime($request->dataInicio));
            if($request->contaFixa == 'on'){
                $data = null;
            }
            DB::table('tab_despesas')->insert([
                'usuario' => auth()->user()->id,
                'descricao' => $request->nomeDespesa,
                'valor' => $despesa,
                'tipo_gasto' => $request->tipoDespesa,
                'data_cobranca' => $data,
                'data_inicio' => $data,
                'tipo_pagamento' => $request->opcaoPagamento,
                'despesa_fixa' => (($request->contaFixa == 'on') ? 'S' : 'N'),
                'atrelamento' => $request->atrelamento,
            ]);
        }

        return redirect('/contas');
    }

    public function editaDespesa(Request $request)
    {
        $despesa = ContasController::retiraMascaraDinheiro($request->valorDespesaEdit);
        $idDespesa = $request->idDespesaEdit;

        if ($request->opcaoPagamentoEdit == 'CREDITO') {
            $dataInicio = Carbon::parse($request->dataInicioEdit);
            $dataFim = Carbon::parse($request->dataFimEdit);

            // Calcula a diferença entre as datas
            $diferenca = $dataInicio->diffInMonths($dataFim) + 1;

            $dataFim = $request->dataFimEdit;
            if (date('Y-m', strtotime($request->dataInicioEdit)) > date('Y-m', strtotime($dataFim))) {
                $dataFim = $request->dataInicioEdit;
            }
            if (date('Y-m', strtotime($request->dataInicioEdit)) == date('Y-m', strtotime($dataFim))) {
                $diferenca = 1;
            }
            $valorMensal = $despesa / $diferenca;
            $dataCobranca = date('Y-m-d', strtotime($request->dataInicioEdit));

            DB::table('tab_despesas')
                ->where('id_despesa', $idDespesa)
                ->limit(1)
                ->update(array(
                    'descricao' => $request->nomeDespesaEdit,
                    'valor' => $despesa,
                    'tipo_gasto' => $request->tipoDespesaEdit,
                    'data_cobranca' => $dataCobranca,
                    'data_inicio' => date('Y-m-d', strtotime($request->dataInicioEdit)),
                    'data_fim' => (($dataFim != '') ? date('Y-m-d', strtotime($dataFim)) : date('Y-m-d')),
                    'tipo_pagamento' => $request->opcaoPagamentoEdit,
                    'atrelamento' => $request->atrelamentoEdit,
                    'nmr_parcelas' => $diferenca,
                ));

            DB::table('tab_parcelas')
                ->where('id_despesa', $idDespesa)
                ->delete();

            for ($i = 0; $i < $diferenca; $i++) {
                $dataCobranca = date('Y-m-d', strtotime($request->dataInicioEdit . ' +' . $i . ' month'));
                DB::table('tab_parcelas')->insert([
                    'id_despesa' => $idDespesa,
                    'data' => $dataCobranca,
                    'parcela_atual' => $i + 1,
                    'valor_quebrado' => $valorMensal,
                ]);
            }
        } else {
            $data = date('Y-m-d', strtotime($request->dataInicioEdit));
            if($request->contaFixaEdit == 'on'){
                $data = null;
            }
            DB::table('tab_despesas')
                ->where('id_despesa', $idDespesa)
                ->limit(1)
                ->update(array(
                    'descricao' => $request->nomeDespesaEdit,
                    'valor' => $despesa,
                    'tipo_gasto' => $request->tipoDespesaEdit,
                    'data_cobranca' => $data,
                    'data_inicio' => $data,
                    'tipo_pagamento' => $request->opcaoPagamentoEdit,
                    'despesa_fixa' => (($request->contaFixaEdit == 'on') ? 'S' : 'N'),
                    'atrelamento' => $request->atrelamentoEdit,
                ));
        }

        return redirect('/contas');
    }

    public function alterarData(Request $request)
    {
        session()->put('dataAnoMes', $request->novaData);

        return redirect()->back();
    }

    public static function retiraMascaraDinheiro($valor)
    {
        if (strpos($valor, 'R$') !== false) {
            $novoValor = trim(str_replace('R$', '', $valor));
            $novoValor = trim(str_replace('.', '', $novoValor));
            $novoValor = floatval(trim(str_replace(',', '.', $novoValor)));

            return $novoValor;
        }

        return $valor;
    }
}
