<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TipoDespesaController extends Controller
{
    public function carregaTipoDespesa(){
        $tiposDespesas = DB::select('select * from tab_tiposgastos where id_usuario = "' . auth()->user()->id . '" and status = "A"', []);

        return view('tipodespesas', compact('tiposDespesas'));
    }

    public function insereTipoDespesa(Request $request)
    {
        DB::table('tab_tiposgastos')->insert([
            'id_usuario' => auth()->user()->id,
            'nome_gasto' => $request->tipoDespesaCad,
        ]);

        return redirect('/tipodespesas');
    }

    public function editaTipoDespesa(Request $request){
        if(!empty($request->idTipoDespesaEdit)){
            DB::table('tab_tiposgastos')
            ->where('id_tipo', $request->idTipoDespesaEdit)
            ->limit(1)
            ->update(array(
                'nome_gasto' => $request->tipoDespesaCadEdit,
            ));
        }

        return redirect('/tipodespesas');
    }

    public function excluiTipoDespesa(Request $request){

        if(!empty($request->idTipoDespesaExcluir)){
            $tiposDespesas = DB::select('select * from tab_despesas where tipo_gasto = "' . $request->idTipoDespesaExcluir . '"');
            
            if(count($tiposDespesas) == 0){
                DB::table('tab_tiposgastos')
                ->where('id_tipo', $request->idTipoDespesaExcluir)
                ->limit(1)
                ->update(array(
                    'status' => 'D'
                ));
            }
        }

        return redirect('/tipodespesas');
    }
}
