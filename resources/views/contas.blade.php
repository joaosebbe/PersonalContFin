@extends('layouts.main')

@section('title', 'Contas')

@section('content')

<div class="row mt-5 mb-5">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-lg-8 col-xl-6">
                <div class="card rounded-3">
                    <div class="card-body">

                        <p class="mb-2"><span class="h2 me-2">Despesas</span></p>

                        <div class="row mt-4">
                            <div class="col-6">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalReceitaUnica"
                                    style="width: 100%">Nova Receita</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-warning text-light" data-bs-toggle="modal"
                                    data-bs-target="#modalNovaDespesa" style="width: 100%">Nova Despesa</button>
                            </div>
                        </div>

                        <div class="container">
        
                            <p class="mt-3" style="color: rgb(165, 164, 164)"><small>Obs: Clique na linha da despesa para edita-la.</small>
                            </p>
                    
                            <div class="row mt-1">
                                <table class="table table-striped table-hover table-sm" style="zoom: 70%">
                                    <thead>
                                        <tr>
                                            <th scope="col">DESC</th>
                                            <th scope="col">VALOR</th>
                                            <th scope="col">PGTO</th>
                                            <th scope="col">PARCELA</th>
                                            <th scope="col">DATA</th>
                                            <th scope="col">DATA FIM</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        @foreach ($despesas as $despesa)
                                            @if ($despesa->receita_despesa == 'D')
                                                <tr data-bs-toggle="modal" data-bs-target="#modalEditaDespesa"
                                                    onclick="modalEditaDespesa('{{ $despesa->id_despesa }}', '{{ $despesa->descricao }}', '{{ $despesa->valor }}', '{{ $despesa->tipo_gasto }}', '{{ $despesa->data_inicio }}', '{{ $despesa->nmr_parcelas }}', '{{ $despesa->tipo_pagamento }}', '{{ $despesa->atrelamento }}', '{{ $despesa->despesa_fixa }}')"
                                                     style="cursor: pointer">
                                            @else
                                                <tr style="cursor: pointer">
                                            @endif
                                            <td>{{ $despesa->descricao }}</td>
                                            <td>R$
                                                @if ($despesa->tipo_pagamento == 'CREDITO' && intval($despesa->nmr_parcelas) > 1)
                                                    {{ number_format($despesa->valor_quebrado, 2, ',', '.') }}
                                                @else
                                                    {{ number_format($despesa->valor, 2, ',', '.') }}
                                                @endif
                                            </td>
                                            <td>{{ $despesa->tipo_pagamento }}</td>
                                            <td>{{ $despesa->parcela_atual . ' / ' . $despesa->nmr_parcelas }}</td>
                                            <td>{{ (($despesa->data_inicio != '') ? date('m/Y', strtotime($despesa->data_inicio)) : '') }}</td>
                                            <td>{{ $despesa->data_fim != '' ? date('m/Y', strtotime($despesa->data_fim)) : '' }}
                                            </td>
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
                                                        <input type="text" id="receitaUnica" name="receitaUnica" class="form-control money"
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
                                                        <input type="text" id="valorDespesa" name="valorDespesa" class="form-control money"
                                                            required>
                                                    </div>
                                                    <div class="col-12 mt-3">
                                                        <label for="opcaoPagamento">Opção Pagamento</label>
                                                        <select name="opcaoPagamento" id="opcaoPagamento" class="form-control" onchange="apareceDataFim()" required>
                                                            <option value="">Escolha uma opção</option>
                                                            <option value="PIX">PIX</option>
                                                            <option value="CREDITO">Crédito</option>
                                                            <option value="DEBITO">Débito</option>
                                                            <option value="DINHEIRO">Dinheiro</option>
                                                            <option value="BOLETO">Boleto</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6 mt-3">
                                                        <label for="dataInicio">Primeira Cobrança</label>
                                                        <input type="date" name="dataInicio" id="dataInicio" class="form-control"
                                                            required>
                                                    </div>
                                                    <div class="col-6 mt-3" id="divDataFim" style="display: none">
                                                        <label for="dataFim">Parcelas</label>
                                                        <input type="number" min="1" name="dataFim" id="dataFim" class="form-control">
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
                            <!-- Modal Edita Despesa -->
                            <div class="modal fade" id="modalEditaDespesa" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Despesa</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="{{ route('editaDespesa') }}">
                                                @csrf
                                                <input type="hidden" name="idDespesaEdit" id="idDespesaEdit">
                                                <div class="row form-group">
                                                    <div class="col-12">
                                                        <label for="nomeDespesaEdit">Despesa</label>
                                                        <input type="text" id="nomeDespesaEdit" name="nomeDespesaEdit"
                                                            placeholder="Ex: Tênis Nike" maxlength="40" class="form-control" required>
                                                    </div>
                                                    <div class="col-12 mt-3">
                                                        <label for="valorDespesaEdit">Valor</label>
                                                        <input type="text" id="valorDespesaEdit" name="valorDespesaEdit"
                                                            class="form-control money" required>
                                                    </div>
                                                    <div class="col-12 mt-3">
                                                        <label for="opcaoPagamentoEdit">Opção Pagamento</label>
                                                        <select name="opcaoPagamentoEdit" id="opcaoPagamentoEdit" class="form-control" onchange="apareceDataFimEdit()"
                                                            required>
                                                            <option value="">Escolha uma opção</option>
                                                            <option value="PIX">PIX</option>
                                                            <option value="CREDITO">Crédito</option>
                                                            <option value="DEBITO">Débito</option>
                                                            <option value="DINHEIRO">Dinheiro</option>
                                                            <option value="BOLETO">Boleto</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6 mt-3">
                                                        <label for="dataInicioEdit">Primeira Cobrança</label>
                                                        <input type="date" name="dataInicioEdit" id="dataInicioEdit" class="form-control"
                                                            required>
                                                    </div>
                                                    <div class="col-6 mt-3" id="divDataFimEdit" style="display: none">
                                                        <label for="dataFimEdit">Parcelas</label>
                                                        <input type="number" min="1" name="dataFimEdit" id="dataFimEdit" class="form-control">
                                                    </div>
                                                    <div class="col-12 mt-3">
                                                        <label for="tipoDespesaEdit">Tipo Despesa</label>
                                                        <select name="tipoDespesaEdit" id="tipoDespesaEdit" class="form-control" required>
                                                            <option value="">Escolha uma opção</option>
                                                            @foreach ($tiposDespesas as $item)
                                                                <option value="{{ $item->id_tipo }}">{{ $item->nome_gasto }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 mt-3">
                                                        <label for="atrelamentoEdit">Atrelamento <small>(opcional)</small></label>
                                                        <select name="atrelamentoEdit" id="atrelamentoEdit" class="form-control">
                                                            <option value="">Escolha uma opção</option>
                                                            @foreach ($atrelamentos as $item)
                                                                <option value="{{ $item->id_atrelamento }}">{{ $item->nome_atrelamento }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 mt-3">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch"
                                                                id="contaFixaEdit" name="contaFixaEdit">
                                                            <label class="form-check-label" for="contaFixaEdit">Clique aqui se essa for uma
                                                                conta
                                                                fixa</label>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="modal-footer" style="display: block">
                                            
                                            <button type="submit" class="btn btn-primary" style="float: right">Salvar</button>
                                            </form>
                    
                                            <form action="{{ route('excluiDespesa') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="idDespesaExcluir" id="idDespesaExcluir">
                                                <input type="hidden" name="tpPgto" id="tpPgto">
                                                <button type="submit" class="btn btn-danger" style="float: left">Excluir</button>
                                            </form>
                    
                                            <form action="{{ route('pararPagamento') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="idDespesaParar" id="idDespesaParar">
                                                <button type="submit" class="btn btn-warning" id="btnPararPagar" style="float: left; margin: 0 10px">Parei de Pagar</button>
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
</div>

    

    

@endsection

@section('script')

    <script>
        function modalEditaDespesa(id, descricao, valor, tipoDesp, dataInicio, parcelas, tipoPgto, atrelamento, despFixa) {
            $("#idDespesaEdit").val(id);
            $("#nomeDespesaEdit").val(descricao);
            $("#valorDespesaEdit").val(valor);
            $("#opcaoPagamentoEdit").val(tipoPgto);
            $("#dataInicioEdit").val(dataInicio);
            $("#dataFimEdit").val(parcelas);
            $("#tipoDespesaEdit").val(tipoDesp);
            $("#atrelamentoEdit").val(atrelamento);

            //Define para form de excluir
            $("#idDespesaExcluir").val(id);
            $("#tpPgto").val(tipoPgto);

            //Define para form de parar pagamento
            $("#idDespesaParar").val(id);

            if (despFixa == 'S') {
                $('#contaFixaEdit').prop('checked', true);
                document.getElementById("btnPararPagar").style.display = "block";
            }else{
                document.getElementById("btnPararPagar").style.display = "none";
            }

            if(dataFim != ""){
                document.getElementById("divDataFimEdit").style.display = "block";
            }
        }

        function apareceDataFim(){
            var opcao = document.getElementById("opcaoPagamento").value;
            var divDataFim = document.getElementById("divDataFim");

            if(opcao == "CREDITO"){
                divDataFim.style.display = "block";
            }else{
                divDataFim.style.display = "none";
            }
        }

        function apareceDataFimEdit(){
            var opcao = document.getElementById("opcaoPagamentoEdit").value;
            var divDataFim = document.getElementById("divDataFimEdit");

            if(opcao == "CREDITO"){
                divDataFim.style.display = "block";
            }else{
                divDataFim.style.display = "none";
            }
        }
    </script>

    <script>
        $(document).ready(function(){
            $('.money').mask('000.000.000.000.000,00', {reverse: true});
        });
        $(function() {
            // $('#receita').maskMoney({
            //     prefix: 'R$ ',
            //     allowNegative: true,
            //     thousands: '.',
            //     decimal: ',',
            //     affixesStay: true
            // });
        })
    </script>

@endsection
