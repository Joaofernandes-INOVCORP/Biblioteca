<?php

use App\Models\Livro;
use App\Models\User;
use function Pest\Laravel\actingAs;

it('confirma se não é possível requisitar um livro sem stock disponível', function () {
    $user = User::factory()->create();

    $livro = Livro::factory()->create(['stock' => 0]);

    $data = [
        'livro_id' => $livro->id,
    ];

    actingAs($user)->post('/requisicoes', $data)->assertSessionHasErrors();
});
