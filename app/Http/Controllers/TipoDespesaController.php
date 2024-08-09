<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TipoDespesaController extends Controller
{
    public function carregaTipoDespesa(){
        $tiposDespesas = DB::select('select * from tab_tiposgastos where id_usuario = ?', [auth()->user()->id]);

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
}
