@extends('layouts.main')

@section('title', 'ControlFin - Contas')

@section('content')

    <div class="container mt-4">
        <form method="POST" action="{{ route('alterarReceita') }}">
            @csrf
            <div class="row form-group">
                <div class="col-9">
                    <label for="receita">Receita Fixa Mensal</label>
                    <input type="text" id="receita" name="receita" class="form-control"
                        value="{{ auth()->user()->valor_receita }}">
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-success mt-4" style="width: 100%">Alterar</button>
                </div>
            </div>
        </form>
        <div class="row mt-4">
            <div class="col-6">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalReceitaUnica"
                    style="width: 100%">Add Receita Única</button>
            </div>
            <div class="col-6">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTipoDespesa"
                    style="width: 100%">Add Tipo Despesa</button>
            </div>
            <div class="col-6 mt-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAtrelamentos"
                    style="width: 100%">Add Atrelamentos</button>
            </div>
            <div class="col-6 mt-2">
                <button type="button" class="btn btn-warning text-light" data-bs-toggle="modal"
                    data-bs-target="#modalNovaDespesa" style="width: 100%">Add Nova Despesa</button>
            </div>
        </div>

        <div class="row mt-5">
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th scope="col">Desc</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Pgto</th>
                        <th scope="col">Dt Inicio</th>
                        <th scope="col">Dt Fim</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($despesas as $despesa)
                        <tr class="{{ $despesa->receita_despesa == 'R' ? 'table-success' : 'table-danger' }}">
                            <td>{{ $despesa->descricao }}</td>
                            @php
                                if ($despesa->tipo_pagamento == 'CREDITO') {
                                    $dataInicio = new DateTime($despesa->data_inicio);
                                    $dataFim = new DateTime($despesa->data_fim);

                                    // Calcula a diferença entre as datas
                                    $diferenca = $dataInicio->diff($dataFim);

                                    // Obtém a diferença em meses
                                    $diferencaMeses = $diferenca->y * 12 + $diferenca->m;

                                    $valorMensal = $despesa->valor / $diferencaMeses;
                                }
                            @endphp
                            <td>R$ {{ number_format($valorMensal, 2, ',', '.') }}</td>
                            <td>{{ $despesa->tipo_pagamento }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <!-- Modal Receita Unica -->
        <div class="modal fade" id="modalReceitaUnica" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Adicionar Receita Única <small>(mês
                                atual)</small></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('insereReceitaUnica') }}">
                            @csrf
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="descRecUnica">Descrição Receita</label>
                                    <input type="text" id="descRecUnica" name="descRecUnica"
                                        placeholder="Ex: Freelancer site" class="form-control" required>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="receitaUnica">Valor Receita</label>
                                    <input type="text" id="receitaUnica" name="receitaUnica" class="form-control"
                                        required>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Tipo Despesa -->
        <div class="modal fade" id="modalTipoDespesa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastrar Tipo Despesa</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('insereTipoDespesa') }}">
                            @csrf
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="tipoDespesaCad">Tipo Despesa</label>
                                    <input type="text" id="tipoDespesaCad" name="tipoDespesaCad"
                                        placeholder="Ex: Streaming, lazer, saúde, etc..." maxlength="40"
                                        class="form-control" required>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Atrelamentos -->
        <div class="modal fade" id="modalAtrelamentos" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastrar Atrelamento</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Os atrelamentos são para referenciar as contas no checklist de pagamentos, por exemplo: ao
                            cadastrar um atrelamento como "Cartão Inter" e quando adicionar uma dispesa a esse nome, no
                            checklist vai aparecer a soma de todas as despesas atreladas ao Cartão Inter</h6>
                        <form method="POST" action="{{ route('insereAtrelamento') }}">
                            @csrf
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="tipoAtrelamento">Atrelamento</label>
                                    <input type="text" id="tipoAtrelamento" name="tipoAtrelamento"
                                        placeholder="Ex: Cartão Inter, Cartão Santander..." maxlength="40"
                                        class="form-control" required>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Nova Despesa -->
        <div class="modal fade" id="modalNovaDespesa" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Adicionar Nova Despesa</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('insereDespesa') }}">
                            @csrf
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="nomeDespesa">Despesa</label>
                                    <input type="text" id="nomeDespesa" name="nomeDespesa"
                                        placeholder="Ex: Tênis Nike" maxlength="40" class="form-control" required>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="valorDespesa">Valor</label>
                                    <input type="text" id="valorDespesa" name="valorDespesa" class="form-control"
                                        required>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="opcaoPagamento">Opção Pagamento</label>
                                    <select name="opcaoPagamento" id="opcaoPagamento" class="form-control" required>
                                        <option value="">Escolha uma opção</option>
                                        <option value="PIX">PIX</option>
                                        <option value="CREDITO">Crédito</option>
                                        <option value="DEBITO">Débito</option>
                                        <option value="DINHEIRO">Dinheiro</option>
                                        <option value="BOLETO">Boleto</option>
                                    </select>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="dataInicio">Data</label>
                                    <input type="date" name="dataInicio" id="dataInicio" class="form-control"
                                        required>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="dataFim">Data Fim <small>(opcional)</small></label>
                                    <input type="date" name="dataFim" id="dataFim" class="form-control" required>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="tipoDespesa">Tipo Despesa</label>
                                    <select name="tipoDespesa" id="tipoDespesa" class="form-control" required>
                                        <option value="">Escolha uma opção</option>
                                        @foreach ($tiposDespesas as $item)
                                            <option value="{{ $item->id_tipo }}">{{ $item->nome_gasto }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="atrelamento">Atrelamento <small>(opcional)</small></label>
                                    <select name="atrelamento" id="atrelamento" class="form-control">
                                        <option value="">Escolha uma opção</option>
                                        @foreach ($atrelamentos as $item)
                                            <option value="{{ $item->id_atrelamento }}">{{ $item->nome_atrelamento }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="contaFixa"
                                            name="contaFixa">
                                        <label class="form-check-label" for="contaFixa">Clique aqui se essa for uma conta
                                            fixa</label>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        $(function() {
            $('#receita').maskMoney({
                prefix: 'R$ ',
                allowNegative: true,
                thousands: '.',
                decimal: ',',
                affixesStay: true
            });
            $('#receitaUnica').maskMoney({
                prefix: 'R$ ',
                allowNegative: true,
                thousands: '.',
                decimal: ',',
                affixesStay: true
            });
            $('#valorDespesa').maskMoney({
                prefix: 'R$ ',
                allowNegative: true,
                thousands: '.',
                decimal: ',',
                affixesStay: true
            });
        })
    </script>

@endsection
