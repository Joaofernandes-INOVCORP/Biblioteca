<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\ReviewController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsCidadao;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\EditoraController;
use App\Http\Controllers\RequisicaoController;

Route::get('/', function () {
     return view('welcome');
})->name("home");

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
     Route::resource('requisicoes', RequisicaoController::class)
          ->only(['index', 'store', 'show', 'update']);

     // Livros só admin
     Route::middleware(IsAdmin::class)
          ->resource('livros', LivroController::class)
          ->except(['index', 'show']);

     Route::middleware(IsAdmin::class)
          ->get('/livros/export', [LivroController::class, "export"]);

     Route::middleware(IsAdmin::class)->post("/livros/create", [LivroController::class, "create"]);

     Route::resource('cart', CartController::class)
          ->only(['index', 'store', 'update', 'delete']);

     Route::middleware(IsCidadao::class)
          ->resource('reviews', ReviewController::class)
          ->only(['store']);

     Route::middleware(IsAdmin::class)
          ->resource('reviews', ReviewController::class)
          ->only(['update', 'index', 'show']);

     Route::middleware('auth')->post('/checkout', [CheckoutController::class, 'finalizarPagamento'])->name('checkout');
     Route::middleware('auth')->post('/enviar/', [CheckoutController::class, 'enviado'])->name('encomenda.update');
     Route::middleware('auth')->get('/checkout/sucesso', [CheckoutController::class, 'sucesso'])->name('checkout.sucesso');
     Route::middleware('auth')->get('/checkout/cancelado', [CheckoutController::class, 'cancelado'])->name('checkout.cancelado');
});


Route::resource('livros', LivroController::class)
     ->only(['index', 'show']);

Route::resource('autores', AutorController::class)
     ->only(['index', 'show']);

Route::resource('editoras', EditoraController::class)
     ->only(['index', 'show']);

Route::post('google-books/', [LivroController::class, 'viaGoogle'])
     ->name('google-books-isbn');

Route::post('/livros/{livro}/notificar-disponivel', [LivroController::class, 'notificarDisponivel'])
     ->middleware('auth')
     ->name('notificar.disponivel');


