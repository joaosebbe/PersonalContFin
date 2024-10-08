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
            {{-- <h5 class="card-title mt-2">Despesas</h5> --}}
            <div class="card-body" id="chartDespesa" style="width: 100%;">

            </div>
        </div>
        <div class="card text-bg shadow-lg mb-5" style="padding: 0 !important;">
            {{-- <h5 class="card-title mt-2">Despesas</h5> --}}
            <div class="card-body" id="chart12months" style="width: 100%; padding: 0 !important;">

            </div>
        </div>
    </div>


@endsection

@section('script')
    <script>
        var options = {
            chart: {
                type: 'donut',
                height: 350
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
            ],
            title: {
                text: 'Despesas Categorias (mês atual)',
                floating: true,
                offsetY: -5,
                align: 'center',
                style: {
                    color: '#444'
                }
            }
        }

        var chart = new ApexCharts(document.querySelector("#chartDespesa"), options);

        chart.render();
    </script>

    <script>
        var options2 = {
            series: [{
                name: 'Despesa',
                data: [
                    @for ($i = 0; $i < count($arrayVlTot); $i++)
                        {{ $arrayVlTot[$i] }},
                    @endfor
                ]
            }],
            chart: {
                height: 350,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    borderRadius: 5,
                    colors: {
                        ranges: [{
                            from: 0,
                            to: 1000000000,
                            color: '#FEB019'
                        }]
                    },
                    
                }
            },
            dataLabels: {
                enabled: false // Esta é outra configuração global que deve ser desativada
            },
            xaxis: {
                categories: [
                    @for ($i = -6; $i < 6; $i++)
                        '{{ $arrayMonths[date('m', strtotime(session()->get('dataAnoMes') . ' ' . $i . ' months'))] }}',
                    @endfor
                ],
                position: 'bottom',
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return value.toLocaleString('pt-BR', {
                            minimumFractionDigits: 2
                        });
                    }
                }
            },
            title: {
                text: 'Despesas 12 meses',
                floating: true,
                offsetY: 0,
                align: 'center',
                style: {
                    color: '#444'
                }
            }
        };

        var chart2 = new ApexCharts(document.querySelector("#chart12months"), options2);
        chart2.render();
    </script>


@endsection
