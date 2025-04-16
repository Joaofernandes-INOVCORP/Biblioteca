<?php

use App\Http\Controllers\LivroController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    Route::resource('livros', LivroController::class);

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/autores', function(){
    return view('autores');
});

Route::get('/editoras', function(){
    return view('editoras');
});