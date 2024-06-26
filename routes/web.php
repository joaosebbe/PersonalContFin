<?php

use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ContasController;
use App\Http\Controllers\DespesasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReceitaController;

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
Route::post('/tipoDespesa', [ContasController::class, 'insereTipoDespesa'])->name('insereTipoDespesa');
Route::post('/atrelamento', [ContasController::class, 'insereAtrelamento'])->name('insereAtrelamento');
Route::post('/alterarData', [ContasController::class, 'alterarData'])->name('alterarData');


Route::get('/inicio', [DespesasController::class, 'retornaDespesas'])->middleware('auth.check');
Route::post('/despesa', [DespesasController::class, 'insereDespesa'])->name('insereDespesa');
Route::post('/editaDespesa', [DespesasController::class, 'editaDespesa'])->name('editaDespesa');
Route::post('/excluiDespesa', [DespesasController::class, 'excluiDespesa'])->name('excluiDespesa');
Route::post('/pararPagamento', [DespesasController::class, 'pararPagamento'])->name('pararPagamento');


Route::post('/alterarReceita', [ReceitaController::class, 'alterarReceita'])->name('alterarReceita');
Route::post('/receitaUnica', [ReceitaController::class, 'insereReceitaUnica'])->name('insereReceitaUnica');


Route::get('/checklist', [ChecklistController::class, 'carregaChecklist']);
Route::get('/pagaContaAtrelamento/{codAtrelamento}', [ChecklistController::class, 'pagaContaAtrelamento']);
Route::get('/pagaContaFixa/{codDespesa}', [ChecklistController::class, 'pagaContaFixa']);
