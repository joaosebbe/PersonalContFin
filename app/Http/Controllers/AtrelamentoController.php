<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AtrelamentoController extends Controller
{
    public function carregaAtrelamentos(){
        $atrelamentos = DB::select('select * from tab_atrelamento where id_usuario = "' . auth()->user()->id . '" and status = "A"');

        return view('atrelamentodespesas', compact('atrelamentos'));
    }

    public function insereAtrelamento(Request $request)
    {
        DB::table('tab_atrelamento')->insert([
            'nome_atrelamento' => $request->tipoAtrelamento,
            'id_usuario' => auth()->user()->id,
            'dia_vencimento' => $request->diaVencimento
        ]);

        return redirect('/atrelamentodespesas');
    }

    public function editaAtrelamento(Request $request){
        if(!empty($request->idAtrelamentoEdit)){
            DB::table('tab_atrelamento')
            ->where('id_atrelamento', $request->idAtrelamentoEdit)
            ->limit(1)
            ->update(array(
                'nome_atrelamento' => $request->tipoAtrelamentoEdit,
                'dia_vencimento' => $request->diaVencimentoEdit
            ));
        }

        return redirect('/atrelamentodespesas');
    }

    public function excluiAtrelamento(Request $request){

        if(!empty($request->idAtrelamentoExcluir)){
            $atrelamentos = DB::select('select * from tab_despesas where atrelamento = "' . $request->idAtrelamentoExcluir . '"');
            
            if(count($atrelamentos) == 0){
                DB::table('tab_atrelamento')
                ->where('id_atrelamento', $request->idAtrelamentoExcluir)
                ->limit(1)
                ->update(array(
                    'status' => 'D'
                ));
            }
        }

        return redirect('/atrelamentodespesas');
    }

    public static function buscaAtrelamentoPorId($id){
        $atrelamentos = DB::select('select * from tab_atrelamento where id_atrelamento = "' . $id . '"');

        return $atrelamentos[0]->dia_vencimento;
    }
}
