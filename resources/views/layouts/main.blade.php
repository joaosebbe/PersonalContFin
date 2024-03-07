<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body style="height: 2000px; padding: 10px">
    <header>
        <a style="float: right;position: fixed; top: 0px; right: 0px; padding:10px; z-index: 5;" href="{{ route('logout') }}" class="text-danger" ><i
            class="fas fa-lg fa-sign-out-alt"></i></a>
        <nav class="navbar fixed-bottom bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand text-primary" href="#"><i class="fas fa-chart-bar fa-lg"></i></a>
                <a class="navbar-brand text-primary" href="#"><i class="fas fa-lg fa-home"></i></a>
                <a class="navbar-brand text-primary" href="#"><i class="far fa-check-square fa-lg"></i></a>
            </div>
        </nav>
    </header>


    <main class="form-signin w-100 m-auto">

        @yield('content')

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</body>

</html>
