@extends('layouts.main')

@section('title', 'ControlFin - Contas')

@section('content')

    <div class="container mt-3">
        <form method="POST" action="{{ route('alterarReceita') }}">
            @csrf
            <div class="row form-group">
                <div class="col-9">
                    <label for="receita">Receita Fixa Mensal</label>
                    <input type="text" id="receita" name="receita" class="form-control" value="{{ auth()->user()->valor_receita }}">
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-primary mt-4">Alterar</button>
                </div>
            </div>
        </form>
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
        })
    </script>

@endsection
