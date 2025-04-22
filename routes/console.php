<?php

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
    //TODO
})->daily();