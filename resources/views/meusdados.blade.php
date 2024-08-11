@extends('layouts.main')

@section('title', 'Meus dados')

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

                            <p class="mb-5">
                                <span class="h2 me-2">Meus dados</span>
                                <a style="float: inline-end" data-bs-toggle="modal" data-bs-target="#modalEditaDados"><i
                                        class="fas fa-edit fa-lg text-primary"></i></a>
                            </p>

                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="nome" readonly class="form-control-plaintext" id="nome"
                                        name="nome" value="{{ auth()->user()->name }}">
                                    <label for="nome">Nome</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="email" readonly class="form-control-plaintext" id="email"
                                        name="email" value="{{ auth()->user()->email }}">
                                    <label for="email">Email</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="telefone" readonly class="form-control-plaintext celular" id="telefone"
                                        name="telefone" value="{{ auth()->user()->telefone }}">
                                    <label for="telefone">Celular</label>
                                </div>
                            </div>

                            <div class="col-12 mt-5">
                                <button type="button" class="btn btn-primary" style="width: 100%" data-bs-toggle="modal"
                                    data-bs-target="#modalAlterarSenha">Alterar minha senha</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Altera Dados -->
    <div class="modal fade" id="modalEditaDados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Alterar Meus Dados</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('editaDados') }}">
                        @csrf
                        <div class="row form-group">
                            <div class="col-12 mb-3">
                                <label for="nomeEdit" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nomeEdit" name="nomeEdit"
                                    value="{{ auth()->user()->name }}" required>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="emailEdit" class="form-label">Email</label>
                                <input type="email" class="form-control" id="emailEdit" name="emailEdit"
                                    value="{{ auth()->user()->email }}" required>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="celularEdit" class="form-label">Celular</label>
                                <input type="text" class="form-control celular" id="celularEdit" name="celularEdit"
                                    value="{{ auth()->user()->telefone }}" required>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Altera Senha -->
    <div class="modal fade" id="modalAlterarSenha" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Alterar Minha Senha</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="formSenha" action="{{ route('alteraSenha') }}">
                        @csrf
                        <div class="row form-group">
                            <div class="col-12 mb-3">
                                <label for="senhaAtual" class="form-label">Senha Atual</label>
                                <input type="password" class="form-control" id="senhaAtual" name="senhaAtual" required>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="senhaNova" class="form-label">Senha Nova</label>
                                <input type="password" class="form-control" id="senhaNova" name="senhaNova" required>
                            </div>

                            <div class="col-12 mb-4">
                                <label for="senhaNovaConfirma" class="form-label">Confirmar Senha Nova</label>
                                <input type="password" class="form-control" id="senhaNovaConfirma"
                                    name="senhaNovaConfirma" required>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="alert alert-danger" id="errorMessages" style="display: none" role="alert">

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="alteraSenha()" class="btn btn-success">Salvar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.celular').mask('(00) 00000-0000');
        });

        async function verificaSenha(password) {
            try {
                const response = await $.ajax({
                    url: '/verificaSenha/' + password,
                    method: 'GET',
                    dataType: 'json'
                });
                console.log(response.cript);
                return response.senhaExiste;
            } catch (error) {
                console.error('Erro na verificação da senha:', error);
                return "false";
            }
        }

        async function alteraSenha() {
            event.preventDefault(); // Impede o envio do formulário para validar os campos primeiro

            let senha = document.getElementById('senhaAtual').value;
            let senhaExiste = await verificaSenha(senha); // Aguarda a verificação da senha
            console.log(senha);
            console.log(senhaExiste);

            let newPassword = document.getElementById("senhaNova").value;
            let confirmPassword = document.getElementById("senhaNovaConfirma").value;
            let errorMessages = [];

            if(senhaExiste == "false") {
                errorMessages.push("A senha atual deve estar correta para prosseguir.");
            }

            // Verifica se a nova senha tem pelo menos 6 caracteres
            if (newPassword.length < 6) {
                errorMessages.push("A nova senha deve ter pelo menos 6 caracteres.");
            }

            // Verifica se a confirmação da nova senha é igual à nova senha
            if (newPassword !== confirmPassword) {
                errorMessages.push("A confirmação da nova senha não corresponde à nova senha.");
            }

            // Exibe as mensagens de erro ou submete o formulário se tudo estiver correto
            if (errorMessages.length > 0) {
                document.getElementById("errorMessages").innerHTML = errorMessages.join("<br>");
                document.getElementById("errorMessages").style.display = "block";
            } else {
                document.getElementById("formSenha").submit();
            }
        }
    </script>
@endsection
