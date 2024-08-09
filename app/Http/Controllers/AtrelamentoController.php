<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AtrelamentoController extends Controller
{
    public function insereAtrelamento(Request $request)
    {
        DB::table('tab_atrelamento')->insert([
            'nome_atrelamento' => $request->tipoAtrelamento,
            'id_usuario' => auth()->user()->id,
        ]);

        return redirect('/atrelamentodespesas');
    }
}
