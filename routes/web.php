<?php

use App\Http\Controllers\ContasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

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

Route::get('/inicio', [ContasController::class, 'retornaDespesas'])->middleware('auth.check');
Route::get('/contas', [ContasController::class, 'carregaSelects']);
Route::get('/checklist', [ContasController::class, 'carregaChecklist']);

Route::post('/alterarReceita', [ContasController::class, 'alterarReceita'])->name('alterarReceita');

Route::post('/receitaUnica', [ContasController::class, 'insereReceitaUnica'])->name('insereReceitaUnica');
Route::post('/tipoDespesa', [ContasController::class, 'insereTipoDespesa'])->name('insereTipoDespesa');
Route::post('/atrelamento', [ContasController::class, 'insereAtrelamento'])->name('insereAtrelamento');
Route::post('/despesa', [ContasController::class, 'insereDespesa'])->name('insereDespesa');
Route::post('/editaDespesa', [ContasController::class, 'editaDespesa'])->name('editaDespesa');

Route::post('/alterarData', [ContasController::class, 'alterarData'])->name('alterarData');