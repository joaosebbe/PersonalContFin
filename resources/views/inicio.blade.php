@extends('layouts.main')

@section('title', 'Início')

@section('content')


    <h1>Bem vindo {{ auth()->user()->name }} </h1>

    <h2>teste</h2>
@endsection