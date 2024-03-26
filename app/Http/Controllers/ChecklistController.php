<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChecklistController extends Controller
{
    public function carregaChecklist()
    {
        $anoMes = session()->get('dataAnoMes');

        $despCredito = DB::select("SELECT SUM(CASE WHEN p.valor_quebrado IS NOT NULL THEN p.valor_quebrado ELSE d.valor END) AS total_valor, MAX(d.id_despesa) AS id_despesa, MAX(a.nome_atrelamento) AS nome_atrelamento, MAX(d.atrelamento) AS atrelamento, MAX(d.descricao) AS descricao, MAX(pg.id_atrelamento) AS id_atrelamento, MAX(pg.id_despesa) AS despPgto
        FROM tab_despesas d 
        LEFT JOIN tab_tiposgastos t ON t.id_tipo = d.tipo_gasto 
        LEFT JOIN tab_atrelamento a ON a.id_atrelamento = d.atrelamento 
        LEFT JOIN tab_parcelas p ON p.id_despesa = d.id_despesa 
        LEFT JOIN tab_pagamentos pg ON (pg.id_atrelamento = d.atrelamento OR pg.id_despesa = d.id_despesa) AND pg.data_pgto LIKE '$anoMes%'
        WHERE d.usuario = '" . auth()->user()->id . "'
        AND (CASE WHEN p.data IS NOT NULL THEN p.data like '$anoMes%' ELSE d.data_cobranca like '$anoMes%' OR (d.data_cobranca IS NULL AND d.atrelamento <> '') END) 
        GROUP BY d.atrelamento ORDER BY nome_atrelamento");

        $despFixa = DB::select("SELECT d.id_despesa, d.descricao, d.valor, p.id_pgto FROM tab_despesas d LEFT JOIN tab_pagamentos p ON p.id_despesa = d.id_despesa AND p.data_pgto LIKE '$anoMes%' WHERE despesa_fixa = 'S' AND usuario = '" . auth()->user()->id . "' AND atrelamento IS NULL ORDER BY d.descricao");


        // return dd($despCredito);
        return view('checklist', compact('despCredito', 'despFixa'));
    }

    public function pagaContaFixa($codDespesa)
    {
        $anoMes = session()->get('dataAnoMes');

        $buscaDespPaga = DB::select("SELECT * FROM tab_pagamentos WHERE id_despesa = '$codDespesa' AND data_pgto LIKE '$anoMes%'");

        $msg = '';

        if (count($buscaDespPaga) > 0) {
            DB::table('tab_pagamentos')
                ->where('id_despesa', $codDespesa)
                ->where('data_pgto', 'LIKE', $anoMes . '%')
                ->delete();
            $msg = 'deletado';
        } else {
            DB::table('tab_pagamentos')->insert([
                'id_despesa' => $codDespesa,
                'data_pgto' => $anoMes . '-' . date('d'),
            ]);
            $msg = 'pago';
        }

        return response()->json([
            'resultado' => $msg
        ]);
    }

    public function pagaContaAtrelamento($codAtrelamento)
    {
        $anoMes = session()->get('dataAnoMes');

        $buscaDespPaga = DB::select("SELECT * FROM tab_pagamentos WHERE id_atrelamento = '$codAtrelamento' AND data_pgto LIKE '$anoMes%'");

        $msg = '';

        if (count($buscaDespPaga) > 0) {
            DB::table('tab_pagamentos')
                ->where('id_atrelamento', $codAtrelamento)
                ->where('data_pgto', 'LIKE', $anoMes . '%')
                ->delete();
            $msg = 'deletado';
        } else {
            DB::table('tab_pagamentos')->insert([
                'id_atrelamento' => $codAtrelamento,
                'data_pgto' => $anoMes . '-' . date('d'),
            ]);
            $msg = 'pago';
        }

        return response()->json([
            'resultado' => $msg
        ]);
    }
}
