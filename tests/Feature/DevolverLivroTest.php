<?php

use App\Models\Livro;
use App\Models\Requisicao;
use App\Models\User;
use function Pest\Laravel\actingAs;

it('confirma se um utilizador pode devolver um livro', function () {

    $user = User::factory()->create(['role' => 'admin']);

    $livro = Livro::factory()->create();

    $data = [
        'livro_id' => $livro->id
    ];

    actingAs($user)->post('/requisicoes', $data)->assertRedirect();

    $data = [
        'action' => 'finish'
    ];

    actingAs($user)->put('/requisicoes/1', $data)->assertSessionDoesntHaveErrors();

    $req = Requisicao::find(1);
    expect($req->status)->toBe('entregue');
});