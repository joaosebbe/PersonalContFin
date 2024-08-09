@extends('layouts.main')

@section('title', 'Tipo Despesas')

@section('style')
    <style>
    </style>
@endsection

@section('content')

    <div class="row" style="padding: 10px">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-lg-8 col-xl-6">
                    <div class="card rounded-3">
                        <div class="card-body p-4">

                            <p class="mb-2"><span class="h2 me-2">Tipo Despesas</span></p>

                            <div class="col-12 mt-5">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTipoDespesa" style="width: 100%">Adicionar Tipo Despesa</button>
                            </div>

                            <div class="container mt-3">
                                <ul class="list-group list-group-flush">
                                    @foreach ($tiposDespesas as $tp)
                                        <li class="list-group-item">
                                            {{ $tp->nome_gasto }}
                                            <a style="float: inline-end" data-bs-toggle="modal" data-bs-target="#modalEditaTipoDespesa"
                                                onclick="modalEditaTipoDespesa('{{ $tp->id_tipo }}', '{{ $tp->nome_gasto }}')"><i
                                                    class="fas fa-edit text-primary"></i></a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
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
                                    placeholder="Ex: Streaming, lazer, saúde, etc..." maxlength="40" class="form-control"
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

    <!-- Modal Edita Tipo Despesa -->
    <div class="modal fade" id="modalEditaTipoDespesa" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastrar Tipo Despesa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('editaTipoDespesa') }}">
                        @csrf
                        <input type="hidden" name="idTipoDespesaEdit" id="idTipoDespesaEdit" value="">
                        <div class="row form-group">
                            <div class="col-12">
                                <label for="tipoDespesaCadEdit">Tipo Despesa</label>
                                <input type="text" id="tipoDespesaCadEdit" name="tipoDespesaCadEdit"
                                    placeholder="Ex: Streaming, lazer, saúde, etc..." maxlength="40" class="form-control"
                                    required>
                            </div>
                        </div>
                </div>
                <div class="modal-footer" style="display: block">
                    <h6>
                        Obs: Caso tenha despesas atreladas não é possivel excluir.
                    </h6>

                    <button type="submit" class="btn btn-primary" style="float: right">Salvar</button>
                    </form>

                    <form action="{{ route('excluiTipoDespesa') }}" method="post">
                        @csrf
                        <input type="hidden" name="idTipoDespesaExcluir" id="idTipoDespesaExcluir" value="">
                        <button type="submit" class="btn btn-danger" style="float: left">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        function modalEditaTipoDespesa(id, nome) {

            $("#idTipoDespesaExcluir").val(id);

            $("#idTipoDespesaEdit").val(id);
            $("#tipoDespesaCadEdit").val(nome);
        }
    </script>

@endsection
