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


        <!-- Modal Receita Unica -->
        <div class="modal fade" id="modalReceitaUnica" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Adicionar Receita Única <small>(mês atual)</small></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            @csrf
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="descRecUnica">Descrição Receita</label>
                                    <input type="text" id="descRecUnica" name="descRecUnica" placeholder="Ex: Freelancer site" class="form-control" required>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="receitaUnica">Valor Receita</label>
                                    <input type="text" id="receitaUnica" name="receitaUnica" class="form-control" required>
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
                        <form method="POST" action="">
                            @csrf
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="tipoDespesa">Tipo Despesa</label>
                                    <input type="text" id="tipoDespesa" name="tipoDespesa" placeholder="Ex: Streaming, lazer, saúde, etc..." maxlength="40" class="form-control" required>
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
        <div class="modal fade" id="modalAtrelamentos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastrar Atrelamento</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Os atrelamentos são para referenciar as contas no checklist de pagamentos, por exemplo: ao cadastrar um atrelamento como "Cartão Inter" e quando adicionar uma dispesa a esse nome, no checklist vai aparecer a soma de todas as despesas atreladas ao Cartão Inter</h6>
                        <form method="POST" action="">
                            @csrf
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="tipoAtrelamento">Atrelamento</label>
                                    <input type="text" id="tipoAtrelamento" name="tipoAtrelamento" placeholder="Ex: Cartão Inter, Cartão Santander..." maxlength="40" class="form-control" required>
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
        })
    </script>

@endsection
