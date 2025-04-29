<?php

use App\Http\Controllers\LivroController;
use App\Http\Middleware\IsAdmin;
use App\Models\Requisicao;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\EditoraController;
use App\Http\Controllers\RequisicaoController;

Route::get('/', function () {
     return view('welcome');
});

Route::middleware([
     'auth:sanctum',
     config('jetstream.auth_session'),
     'verified',
])->group(function () {
     /*Route::resource('livros', LivroController::class) // só admins podem editar livros
          ->except(['index', 'show']);*/

     Route::resource('editoras', EditoraController::class)
          ->except(['index', 'show']);

     Route::get('/dashboard', fn() => view('dashboard'))
          ->name('dashboard');
});

Route::middleware('auth')->group(function () {
     // Requisições (cidadãos e admins)
     Route::resource('requisicoes', RequisicaoController::class)
          ->only(['index', 'store', 'show', 'update']);

     // Livros só admin
     Route::middleware(IsAdmin::class)
          ->resource('livros', LivroController::class)
          ->except(['index', 'show']);

     Route::middleware(IsAdmin::class)
          ->get('/livros/export', [LivroController::class, "export"]);
});


Route::resource('livros', LivroController::class)
     ->only(['index', 'show']);

Route::resource('autores', AutorController::class)
     ->only(['index', 'show']);

Route::resource('editoras', EditoraController::class)
     ->only(['index', 'show']);

