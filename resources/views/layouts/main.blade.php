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

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <link rel="icon" href="{{ url('/img/Meu_ContFin-removebg.png') }}" />
    <link rel="apple-touch-icon" href="{{ url('/img/Meu_ContFin-removebg.png') }}">
    <link rel="manifest" href="manifest_V.1.0.1.json">

    <style>
        body{
            max-width: 1000px;
            margin: 0 auto !important;
        }
        .botoesMenu{
            border: solid 1px #F8F9FA;
            padding: 5px;
            border-radius: 50%;
            transition: all 0.2s ease;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
        }

        .botoesMenu:active {
            transform: translateY(6px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
    </style>

    @yield('style')

</head>

<body style="padding: 10px; background-color:rgb(228, 228, 228)">
    <header>
        <a role="button"
            style="float: left;position: fixed; top: 0px; left: 0px; z-index: 5; background-color: #5c5cf9; color: white; text-decoration: none; padding: 5px; border-radius: 0 0 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);"
            data-bs-toggle="modal"
            data-bs-target="#modalData"><span><strong>{{ !session()->get('dataAnoMes') ? date('m/Y') : date('m/Y', strtotime(session()->get('dataAnoMes'))) }}</strong>&nbsp;&nbsp;<i class="fas fa-calendar"></i></span></a>

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
                                    <input class="form-control" type="month" name="novaData" id="novaData"
                                        value="{{ !session()->get('dataAnoMes') ? date('m/Y') : session()->get('dataAnoMes') }}"
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
        <nav class="navbar fixed-bottom bg-body-tertiary shadow-lg" style="border-radius: 25px 25px 0 0">
            <div class="container-fluid">
                <a class="navbar-brand text-primary botoesMenu" href="/contas"><i class="fas fa-chart-bar fa-lg"></i></a>
                <a class="navbar-brand text-primary botoesMenu" href="/inicio"><i class="fas fa-lg fa-home"></i></a>
                <a class="navbar-brand text-primary botoesMenu" href="/checklist"><i class="fas fa-tasks fa-lg"></i></a>
                <a class="navbar-brand text-primary botoesMenu" href="/config"><i class="fas fa-cogs fa-lg"></i></a>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js" integrity="sha512-0XDfGxFliYJPFrideYOoxdgNIvrwGTLnmK20xZbCAvPfLGQMzHUsaqZK8ZoH+luXGRxTrS46+Aq400nCnAT0/w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    @yield('script')

</body>

</html>
