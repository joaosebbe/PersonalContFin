@extends('layouts.main')

@section('title', 'Início')

@section('style')
    <style>
        .valor {
            font-weight: bold;
        }

        .valorDespesa {
            font-weight: bold;
            color: rgb(250, 113, 113);
            font-size: 20px;
        }

        .valorResultante {
            font-weight: bold;
            color: rgb(120, 180, 96);
            font-size: 20px;
        }
    </style>
@endsection

@section('content')

    <div class="row mt-4" style="padding: 10px">
        <div class="card text-bg shadow-lg mb-3">
            <div class="card-header">
                <h6>Bem vindo,</h6>
                <h2> {{ explode(' ', auth()->user()->name)[0] }} !</h2>
            </div>
            <h5 class="card-title mt-2">Resumo</h5>
            <div class="card-body" style="width: 100%; ">
                <span>
                    Receita <small>(mês atual)</small>: R$ <span
                        class="valor">{{ number_format($valReceita, 2, ',', '.') }}</span>
                </span>
                <br>
                <span>
                    @php
                        $valorTotalDesp = 0;
                        foreach ($despesas as $key) {
                            $valorTotalDesp = $valorTotalDesp + $key->total_valor;
                        }
                        echo 'Despesa: R$ <span
                        class="valorDespesa">' .
                            number_format($valorTotalDesp, 2, ',', '.') .
                            '</span>';
                    @endphp
                </span>
                <br>
                <span>
                    Restante: R$ <span
                        class="valorResultante">{{ number_format($valReceita - $valorTotalDesp, 2, ',', '.') }}</span>
                </span>
            </div>
        </div>
        <div class="card text-bg shadow-lg mb-3">
            <h5 class="card-title mt-2">Despesas</h5>
            <div class="card-body" id="chartDespesa" style="width: 100%; margin-top: -30px; max-height: 400px">

            </div>
        </div>
    </div>


@endsection

@section('script')
    <script>
        var options = {
            chart: {
                type: 'donut'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '55%'
                    }
                }
            },
            series: [
                @foreach ($despesas as $despesa)
                    {{ $despesa->total_valor }},
                @endforeach
            ],
            labels: [
                @foreach ($despesas as $despesa)
                    '{{ $despesa->nome_gasto }}',
                @endforeach
            ]
        }

        var chart = new ApexCharts(document.querySelector("#chartDespesa"), options);

        chart.render();
    </script>


@endsection
