@extends('layouts.main')

@section('title', 'CheckList')

@section('style')

@endsection

@section('content')

    <div class="row mt-4" style="padding: 10px">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-lg-8 col-xl-6">
                    <div class="card rounded-3">
                        <div class="card-body p-4">

                            <p class="mb-2"><span class="h2 me-2">Lista de Pagamentos</span></p>
                            <p class="text-muted pb-2">{{ date('m/Y', strtotime(session()->get('dataAnoMes'))) }}</p>

                            <ul class="list-group rounded-0">
                                @foreach ($despCredito as $dc)
                                    <li class="list-group-item border-0 d-flex align-items-center ps-0">
                                        <input class="form-check-input me-3" type="checkbox" value="" />
                                        {{ ($dc->nome_atrelamento != '' ? $dc->nome_atrelamento : $dc->descricao) . ' = R$ ' . number_format($dc->total_valor, 2, ',', '.') }}
                                    </li>
                                @endforeach

                                @foreach ($despFixa as $df)
                                    <li class="list-group-item border-0 d-flex align-items-center ps-0">
                                        <input class="form-check-input me-3" type="checkbox" value="" />
                                        {{ $df->descricao . ' = R$ ' . number_format($df->valor, 2, ',', '.') }}
                                    </li>
                                @endforeach

                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')



@endsection
