<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DespesasController extends Controller
{
    public function retornaDespesas()
    {
        $anoMes = session()->get('dataAnoMes');
        $despesas = DB::select("
        SELECT SUM(CASE WHEN p.valor_quebrado IS NOT NULL THEN p.valor_quebrado ELSE d.valor END) AS total_valor, t.nome_gasto
        FROM tab_despesas d 
        LEFT JOIN tab_parcelas p ON d.id_despesa = p.id_despesa
        LEFT JOIN tab_tiposgastos t ON t.id_tipo = d.tipo_gasto
        WHERE d.receita_despesa = 'D' AND d.usuario = '" . auth()->user()->id . "' AND ((CASE WHEN p.data IS NOT NULL THEN p.data like '$anoMes%' ELSE d.data_cobranca like '$anoMes%' END) OR (d.despesa_fixa = 'S' AND (d.data_fim IS NULL OR date_format(d.data_fim, '%Y-%m') >= '$anoMes'))) GROUP BY d.tipo_gasto, t.nome_gasto;
        ");

        $receita = new ReceitaController();
        $valReceita = $receita->verificaReceita();

        $arrayMonths = ["01" => "Jan", "02" => "Fev", "03" => "Mar", "04" => "Abr", "05" => "Mai", "06" => "Jun", "07" => "Jul", "08" => "Ago", "09" => "Set", "10" => "Out", "11" => "Nov", "12" => "Dez"];

        $arrayVlTot = array();
        for ($i = -6; $i < 6; $i++) {
            $mes = $arrayMonths[date("m", strtotime($anoMes . ' ' . $i . ' months'))];

            $anoMesChart = date('Y-m', strtotime($anoMes . ' ' . $i . ' months'));

            $despesasMes = DB::select("SELECT SUM(CASE WHEN p.valor_quebrado IS NOT NULL THEN p.valor_quebrado ELSE d.valor END) AS total_valor, t.nome_gasto FROM tab_despesas d LEFT JOIN tab_parcelas p ON d.id_despesa = p.id_despesa LEFT JOIN tab_tiposgastos t ON t.id_tipo = d.tipo_gasto WHERE d.receita_despesa = 'D' AND d.usuario = '" . auth()->user()->id . "' AND ((CASE WHEN p.data IS NOT NULL THEN p.data like '$anoMesChart%' ELSE d.data_cobranca like '$anoMesChart%' END) OR (d.  despesa_fixa = 'S' AND (d.data_fim IS NULL OR date_format(d.data_fim, '%Y-%m') >= '$anoMesChart'))) GROUP BY d.tipo_gasto, t.nome_gasto;
            ");

            $valorTotalDesp = 0;
            foreach($despesasMes as $despMes){
                $valorTotalDesp = $valorTotalDesp + $despMes->total_valor;
            }

            $arrayVlTot[] = $valorTotalDesp;
        }

        // return dd($array);
        return view('inicio', compact('despesas', 'valReceita', 'arrayMonths', 'arrayVlTot'));
    }

    public function insereDespesa(Request $request)
    {
        $despesa = ContasController::retiraMascaraDinheiro($request->valorDespesa);

        if ($request->opcaoPagamento == 'CREDITO') {
            $dataInicio = $request->dataInicio;
            $parcelas = $request->dataFim;

            $valorMensal = $despesa / $parcelas;
            $dataCobranca = date('Y-m-d', strtotime($dataInicio));

            $idDespesa = DB::table('tab_despesas')->insertGetId([
                'usuario' => auth()->user()->id,
                'descricao' => $request->nomeDespesa,
                'valor' => $despesa,
                'tipo_gasto' => $request->tipoDespesa,
                'data_cobranca' => $dataCobranca,
                'data_inicio' => $dataCobranca,
                'data_fim' => date('Y-m-d', strtotime($dataCobranca . ' +' . ($parcelas - 1) . ' month')),
                'tipo_pagamento' => $request->opcaoPagamento,
                'atrelamento' => $request->atrelamento,
                'nmr_parcelas' => $parcelas,
            ]);

            for ($i = 0; $i < $parcelas; $i++) {
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
            if ($request->contaFixa == 'on') {
                $data = null;
                if ($request->atrelamento == '') {
                    $diaVencimento = $request->diaVencimento;
                } else {
                    $diaVencimento = AtrelamentoController::buscaAtrelamentoPorId($request->atrelamento);
                }
            }
            DB::table('tab_despesas')->insert([
                'usuario' => auth()->user()->id,
                'descricao' => $request->nomeDespesa,
                'valor' => $despesa,
                'tipo_gasto' => $request->tipoDespesa,
                'data_cobranca' => $data,
                'data_inicio' => $data,
                'dia_vencimento' => $diaVencimento,
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
            $dataInicio = $request->dataInicioEdit;
            $parcelas = $request->dataFimEdit;

            $valorMensal = $despesa / $parcelas;
            $dataCobranca = date('Y-m-d', strtotime($dataInicio));

            DB::table('tab_despesas')
                ->where('id_despesa', $idDespesa)
                ->limit(1)
                ->update(array(
                    'descricao' => $request->nomeDespesaEdit,
                    'valor' => $despesa,
                    'tipo_gasto' => $request->tipoDespesaEdit,
                    'data_cobranca' => $dataCobranca,
                    'data_inicio' => $dataCobranca,
                    'data_fim' => date('Y-m-d', strtotime($dataCobranca . ' +' . ($parcelas - 1) . ' month')),
                    'tipo_pagamento' => $request->opcaoPagamentoEdit,
                    'atrelamento' => $request->atrelamentoEdit,
                    'nmr_parcelas' => $parcelas,
                ));

            DB::table('tab_parcelas')
                ->where('id_despesa', $idDespesa)
                ->delete();

            for ($i = 0; $i < $parcelas; $i++) {
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
            if ($request->contaFixaEdit == 'on') {
                $data = null;
                if ($request->atrelamentoEdit == '') {
                    $diaVencimento = $request->diaVencimentoEdit;
                } else {
                    $diaVencimento = AtrelamentoController::buscaAtrelamentoPorId($request->atrelamentoEdit);
                }
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
                    'dia_vencimento' => $diaVencimento,
                    'tipo_pagamento' => $request->opcaoPagamentoEdit,
                    'despesa_fixa' => (($request->contaFixaEdit == 'on') ? 'S' : 'N'),
                    'atrelamento' => $request->atrelamentoEdit,
                ));
        }

        return redirect('/contas');
    }

    public function excluiDespesa(Request $request)
    {
        if ($request->idDespesaExcluir) {
            DB::table('tab_despesas')
                ->where('id_despesa', $request->idDespesaExcluir)
                ->delete();
            if ($request->tpPgto == 'CREDITO') {
                DB::table('tab_parcelas')
                    ->where('id_despesa', $request->idDespesaExcluir)
                    ->delete();
            }
        }
        return redirect('/contas');
    }

    public function pararPagamento(Request $request)
    {
        if ($request->idDespesaParar) {
            DB::table('tab_despesas')
                ->where('id_despesa', $request->idDespesaParar)
                ->limit(1)
                ->update(array(
                    'data_fim' => date("Y-m-d"),
                ));
        }
        return redirect('/contas');
    }
}
