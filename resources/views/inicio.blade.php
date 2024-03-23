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
        .valorResultante{
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
                <h6>Bem vindo,</h6><h2> {{ explode(' ', auth()->user()->name)[0] }} !</h2>
            </div>
            <h5 class="card-title mt-2">Resumo</h5>
            <div class="card-body" style="width: 100%; ">
                <span>
                    Receita <small>(mês atual)</small>: R$ <span
                        class="valor">{{ number_format(auth()->user()->valor_receita, 2, ',', '.') }}</span>
                </span>
                <br>
                <span>
                    @php
                        $valorTotalDesp = 0;
                        foreach ($despesas as $key) {
                            $valorTotalDesp = $valorTotalDesp + $key->total_valor;
                        }
                        echo 'Despesa: R$ <span
                        class="valorDespesa">' . number_format($valorTotalDesp, 2, ',', '.') . '</span>';
                    @endphp
                </span>
                <br>
                <span>
                    Restante: R$ <span class="valorResultante">{{ number_format((auth()->user()->valor_receita - $valorTotalDesp), 2 , ',', '.') }}</span>
                </span>
            </div>
        </div>
        <div class="card text-bg shadow-lg mb-3">
            <h5 class="card-title mt-2">Despesas</h5>
            <div class="card-body" id="chartDespesa" style="width: 100%; height: 300px; margin-top: -30px">

            </div>
        </div>
    </div>


@endsection

@section('script')

    <script>
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],
                @foreach ($despesas as $despesa)
                        ['{{ $despesa->nome_gasto }}', {{ $despesa->total_valor }}],
                @endforeach
            ]);

            var options = {
                pieHole: 0.5, // Define o tamanho do buraco no meio do gráfico de rosca
                pieSliceText: 'value', // Exibe os valores dos dados nas fatias do gráfico
                legend: {
                    position: 'right' // Posiciona a legenda na parte inferior do gráfico
                },
                backgroundColor: 'transparent', // Define o fundo do gráfico como transparente
                chartArea: {
                    width: '100%', // Usa toda a largura disponível do contêiner
                    height: '80%' // Usa 80% da altura disponível do contêiner
                }
            };

            var chart = new google.visualization.PieChart(document.getElementById('chartDespesa'));
            chart.draw(data, options);
        }
    </script>


@endsection
