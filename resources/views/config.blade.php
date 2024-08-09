@extends('layouts.main')

@section('title', 'Configurações')

@section('style')
    <style>
        .botoesConfig {
            transition: all 0.2s ease;
        }

        .botoesConfig:active {
            transform: translateX(6px);
            box-shadow: 0 -4px -6px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection

@section('content')

    <div class="row" style="padding: 10px">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-lg-8 col-xl-6">
                    <div class="card rounded-3">
                        <div class="card-body p-4">

                            <p class="mb-2"><span class="h2 me-2">Configurações</span></p>

                            <div class="list-group list-group-flush mt-5">
                                <a href="/meusdados" class="list-group-item list-group-item-action botoesConfig"><i
                                        class="fas fa-user text-primary"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Meus dados</a>
                                <a data-bs-toggle="modal" data-bs-target="#modalReceita"
                                    class="list-group-item list-group-item-action botoesConfig"><i
                                        class="fas fa-money-check-alt text-primary"></i>&nbsp;&nbsp;&nbsp;&nbsp;Receita Fixa
                                    Mensal</a>
                                <a href="/tipodespesas" class="list-group-item list-group-item-action botoesConfig"><i
                                        class="fas fa-credit-card text-primary"></i>&nbsp;&nbsp;&nbsp;&nbsp;Tipo
                                    Despesas</a>
                                <a href="/atrelamentodespesas"
                                    class="list-group-item list-group-item-action botoesConfig"><i
                                        class="fas fa-reply text-primary"></i>&nbsp;&nbsp;&nbsp;&nbsp;Atrelamento
                                    Despesas</a>
                            </div>

                            <!-- Modal Receita -->
                            <div class="modal fade" id="modalReceita" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Receita Fixa Mensal</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="{{ route('alterarReceita') }}">
                                                @csrf
                                                <div class="row form-group">
                                                    <div class="col-12 mt-2 mb-2">
                                                        <input type="text" id="receita" name="receita"
                                                            class="form-control money"
                                                            value="{{ auth()->user()->valor_receita }}">
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

@section('script')
    <script>
        $(document).ready(function() {
            $('.money').mask('000.000.000.000.000,00', {
                reverse: true
            });
        });
    </script>
@endsection
