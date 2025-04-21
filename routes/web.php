<?php

use App\Http\Controllers\LivroController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\EditoraController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('livros',   LivroController::class)
         ->except(['index','show']);
    Route::resource('editoras', EditoraController::class)
         ->except(['index','show']);
    Route::get('/dashboard', fn() => view('dashboard'))
         ->name('dashboard');
});

Route::resource('livros',   LivroController::class)
     ->only(['index','show']);

Route::resource('autores',  AutorController::class)
     ->only(['index','show']);
     
Route::resource('editoras', EditoraController::class)
     ->only(['index','show']);
