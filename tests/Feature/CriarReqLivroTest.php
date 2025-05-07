<?php

use App\Models\Livro;
use App\Models\Requisicao;
use App\Models\User;
use function Pest\Laravel\actingAs;

it('Criar uma requisição', function () {

    $user = User::factory()->create();

    $livro = Livro::factory()->create();

    $data = [
        'livro_id' => $livro->id,
    ];

    $res = actingAs($user)->post('/requisicoes', $data);


    $req = Requisicao::where("livro_id", "=", $livro->id)
        ->where("user_id", "=", $user->id, "and")
        ->first();

    expect($req->livro_id)->toBe($livro->id);
    expect($req->user_id)->toBe($user->id);
});
