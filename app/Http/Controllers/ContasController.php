<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ContasController extends Controller
{
    public function carregaContas()
    {
        $anoMes = session()->get('dataAnoMes');
        $tiposDespesas = DB::select('select * from tab_tiposgastos where id_usuario = ?', [auth()->user()->id]);
        $atrelamentos = DB::select('select * from tab_atrelamento where id_usuario = ?', [auth()->user()->id]);
        $despesas = DB::select("SELECT d.*, t.*, a.*, p.data, p.parcela_atual, p.valor_quebrado 
        FROM tab_despesas d 
        LEFT JOIN tab_tiposgastos t ON t.id_tipo = d.tipo_gasto
        LEFT JOIN tab_atrelamento a ON a.id_atrelamento = d.atrelamento
        LEFT JOIN tab_parcelas p ON p.id_despesa = d.id_despesa
        WHERE d.usuario = '" . auth()->user()->id . "' AND ((CASE WHEN p.data IS NOT NULL THEN p.data like '$anoMes%' ELSE d.data_cobranca like '$anoMes%' END) OR (d.despesa_fixa = 'S' AND (d.data_fim IS NULL OR date_format(d.data_fim, '%Y-%m') >= '$anoMes')))");

        return view('contas', compact('tiposDespesas', 'atrelamentos', 'despesas'));
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

    public function alterarData(Request $request)
    {
        session()->put('dataAnoMes', $request->novaData);

        return redirect()->back();
    }

    public static function retiraMascaraDinheiro($valor)
    {
        $novoValor = trim(str_replace('.', '', $valor));
        $novoValor = floatval(trim(str_replace(',', '.', $novoValor)));

        return $novoValor;
    }
}
