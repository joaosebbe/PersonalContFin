<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>MeuContFin - @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body style="padding: 10px">
    <header>
        <a role="button"
            style="float: left;position: fixed; top: 0px; left: 0px; z-index: 5; background-color: #5c5cf9; color: white; text-decoration: none; padding: 5px; border-radius: 0 0 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);"
            data-bs-toggle="modal" data-bs-target="#modalData"><span><strong>{{ ((!session()->get('dataAnoMes')) ? date('m/Y') : date('m/Y', strtotime(session()->get('dataAnoMes'))) ) }}</strong></span></a>

        <!-- Modal Data -->
        <div class="modal fade" id="modalData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Alterar Data</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form action="{{ route('alterarData') }}" method="post" id="formAlterarData">
                                @csrf
                                <div class="col-12 form-group">
                                    <input class="form-control" type="month" name="novaData" id="novaData" value="{{ ((!session()->get('dataAnoMes')) ? date('m/Y') : session()->get('dataAnoMes')) }}"
                                        onchange="document.getElementById('formAlterarData').submit()">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a style="float: right;position: fixed; top: 0px; right: 0px; padding:10px; z-index: 5;"
            href="{{ route('logout') }}" class="text-danger"><i class="fas fa-lg fa-sign-out-alt"></i></a>
        <nav class="navbar fixed-bottom bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand text-primary" href="/contas"><i class="fas fa-chart-bar fa-lg"></i></a>
                <a class="navbar-brand text-primary" href="/inicio"><i class="fas fa-lg fa-home"></i></a>
                <a class="navbar-brand text-primary" href="#"><i class="far fa-check-square fa-lg"></i></a>
            </div>
        </nav>
    </header>


    <main>

        @yield('content')

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

    @yield('script')

</body>

</html>
