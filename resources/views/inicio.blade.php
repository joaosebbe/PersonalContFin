@extends('layouts.main')

@section('title', 'ControlFin - Início')

@section('content')


    <h1>Bem vindo {{ auth()->user()->name }} </h1>

@endsection