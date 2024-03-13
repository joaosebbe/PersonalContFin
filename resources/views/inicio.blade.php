@extends('layouts.main')

@section('title', 'Início')

@section('style')
    <style>
        .valor {
            font-weight: bold;
        }

        .valorDespesa {
            font-weight: bold;
            color: rgb(255, 0, 0);
        }
    </style>
@endsection

@section('content')

    <div class="row mt-5" style="padding: 10px">
        <div class="card text-bg-success mb-3">
            <div class="card-header">
                <h4>Bem vindo ao seu controle, {{ explode(' ', auth()->user()->name)[0] }} !</h4>
            </div>
            <h5 class="card-title mt-2">Resumo</h5>
            <div class="card-body" style="width: 100%; ">
                <span>
                    Receita Fixa: R$ <span
                        class="valor">{{ number_format(auth()->user()->valor_receita, 2, ',', '.') }}</span>
                </span>
                <br>
                <span>
                    Receita <small>(mês atual)</small>: R$ <span
                        class="valor">{{ number_format(auth()->user()->valor_receita, 2, ',', '.') }}</span>
                </span>
                <br>
                <span>
                    @php
                        $valorTotalDesp = 0;
                        foreach ($despesas as $key) {
                            if ($key->valor_quebrado == '') {
                                $valorTotalDesp = $valorTotalDesp + $key->valor;
                            }else {
                                $valorTotalDesp = $valorTotalDesp + $key->valor_quebrado;
                            }
                            
                        }
                        echo 'Despesa: R$ <span
                        class="valorDespesa">' . number_format($valorTotalDesp, 2, ',', '.') . '</span>';
                    @endphp
                </span>
                <br>
                <span>
                    Restante:
                </span>
            </div>
        </div>
        <div class="card text-bg-warning mb-3">
            <h5 class="card-title mt-2">Suas Despesas</h5>
            <div class="card-body" id="chartDespesa" style="width: 100%; height: 200px;">

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
                    @if ($despesa->valor_quebrado == '')
                        ['{{ $despesa->nome_gasto }}', {{ $despesa->valor }}],
                    @else
                        ['{{ $despesa->nome_gasto }}', {{ $despesa->valor_quebrado }}],
                    @endif
                @endforeach
            ]);

            var options = {
                pieHole: 0.4, // Define o tamanho do buraco no meio do gráfico de rosca
                pieSliceText: 'value', // Exibe os valores dos dados nas fatias do gráfico
                legend: {
                    position: 'right' // Posiciona a legenda na parte inferior do gráfico
                },
                backgroundColor: 'transparent', // Define o fundo do gráfico como transparente
                chartArea: {
                    width: '100%', // Usa toda a largura disponível do contêiner
                    height: '90%' // Usa 80% da altura disponível do contêiner
                }
            };

            var chart = new google.visualization.PieChart(document.getElementById('chartDespesa'));
            chart.draw(data, options);
        }
    </script>


@endsection
