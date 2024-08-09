@extends('layouts.main')

@section('title', 'Atrelamento despesas')

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

                            <p class="mb-2"><span class="h2 me-2">Atrelamento despesas</span></p>

                            <div class="col-12 mt-5">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAtrelamentos"
                                    style="width: 100%">Adicionar Atrelamento</button>
                            </div>

                            <!-- Modal Atrelamentos -->
                            <div class="modal fade" id="modalAtrelamentos" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastrar Atrelamento</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Os atrelamentos são para referenciar as contas no checklist de pagamentos,
                                                por exemplo: ao
                                                cadastrar um atrelamento como "Cartão Inter" e quando adicionar uma dispesa
                                                a esse nome, no
                                                checklist vai aparecer a soma de todas as despesas atreladas ao Cartão Inter
                                            </h6>
                                            <form method="POST" action="{{ route('insereAtrelamento') }}">
                                                @csrf
                                                <div class="row form-group">
                                                    <div class="col-12">
                                                        <label for="tipoAtrelamento">Atrelamento</label>
                                                        <input type="text" id="tipoAtrelamento" name="tipoAtrelamento"
                                                            placeholder="Ex: Cartão Inter, Cartão Santander..."
                                                            maxlength="40" class="form-control" required>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Fechar</button>
                                            <button type="submit" class="btn btn-primary">Salvar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
