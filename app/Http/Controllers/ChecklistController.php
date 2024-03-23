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

        $despCredito = DB::select("
        SELECT SUM(CASE WHEN p.valor_quebrado IS NOT NULL THEN p.valor_quebrado ELSE d.valor END) AS total_valor,d.id_despesa, a.nome_atrelamento, d.descricao FROM tab_despesas d LEFT JOIN tab_tiposgastos t ON t.id_tipo = d.tipo_gasto LEFT JOIN tab_atrelamento a ON a.id_atrelamento = d.atrelamento LEFT JOIN tab_parcelas p ON p.id_despesa = d.id_despesa WHERE d.usuario = '" . auth()->user()->id . "' AND d.tipo_pagamento = 'CREDITO' AND (CASE WHEN p.data IS NOT NULL THEN p.data like '$anoMes%' ELSE d.data_cobranca like '$anoMes%' END) GROUP BY d.atrelamento, d.id_despesa, a.nome_atrelamento, d.descricao
        ");

        $despFixa = DB::select("SELECT * FROM tab_despesas WHERE despesa_fixa = 'S'");

        // return dd($despCredito);
        return view('checklist', compact('despCredito','despFixa'));
    }
}
