<?php

use App\Mail\CarrinhoAjuda;
use App\Mail\RequisicaoAlert;
use App\Models\Cart;
use App\Models\Requisicao;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//excuta diariamente
//vais em requisições e procura por requisições q acabem no dia seguinte e envia a cada user um email de alerta
Schedule::call(function () {
    $reqs = Requisicao::with("user")->where("data_prevista_fim", "=", now()->addDay())->get()->map(function ($r) {
        return $r->user()->pluck("email")->first();
    });
    if ($reqs->count() > 0) {
        Mail::to($reqs->toArray())
            ->send(new RequisicaoAlert($reqs));
    }
})->daily();

Schedule::call(function () {
    $carts = Cart::where('created_at', '<', now()->subHour())->with('user')->get();

    if ($carts->count() > 0) {
        $emails = $carts->map(function ($c) {
            return $c->user->email;
        })->toArray();
        Mail::to($emails)
            ->send(new CarrinhoAjuda($emails));
    }
})->hourly();