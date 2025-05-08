<?php

use App\Models\Livro;
use App\Models\User;
use function Pest\Laravel\actingAs;

it('nao valida uma requisicao de um livro invalido', function(){

    $user = User::factory()->create();

    $data = [
        'livro_id' => 100,
    ];

    actingAs($user)->post('/requisicoes', $data)->assertSessionHasErrors();
});