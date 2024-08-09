<?php

use App\Http\Controllers\AtrelamentoController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ContasController;
use App\Http\Controllers\DespesasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\TipoDespesaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/auth', [LoginController::class, 'auth'])->name('login.auth');
Route::get('/logout', [LoginController::class, 'destroy'])->name('logout');


Route::get('/contas', [ContasController::class, 'carregaContas']);

Route::post('/alterarData', [ContasController::class, 'alterarData'])->name('alterarData');


Route::get('/inicio', [DespesasController::class, 'retornaDespesas'])->middleware('auth.check');
Route::post('/despesa', [DespesasController::class, 'insereDespesa'])->name('insereDespesa');
Route::post('/editaDespesa', [DespesasController::class, 'editaDespesa'])->name('editaDespesa');
Route::post('/excluiDespesa', [DespesasController::class, 'excluiDespesa'])->name('excluiDespesa');
Route::post('/pararPagamento', [DespesasController::class, 'pararPagamento'])->name('pararPagamento');

Route::post('/receitaUnica', [ReceitaController::class, 'insereReceitaUnica'])->name('insereReceitaUnica');

Route::get('/checklist', [ChecklistController::class, 'carregaChecklist']);
Route::get('/pagaContaAtrelamento/{codAtrelamento}', [ChecklistController::class, 'pagaContaAtrelamento']);
Route::get('/pagaContaFixa/{codDespesa}', [ChecklistController::class, 'pagaContaFixa']);


Route::get('/config', function () {
    return view('config');
});
Route::post('/alterarReceita', [ReceitaController::class, 'alterarReceita'])->name('alterarReceita');


Route::get('/meusdados', function () {
    return view('meusdados');
});


Route::get('/tipodespesas', function () {
    return view('tipodespesas');
});
Route::post('/tipoDespesa', [TipoDespesaController::class, 'insereTipoDespesa'])->name('insereTipoDespesa');


Route::get('/atrelamentodespesas', function () {
    return view('atrelamentodespesas');
});
Route::post('/atrelamento', [AtrelamentoController::class, 'insereAtrelamento'])->name('insereAtrelamento');