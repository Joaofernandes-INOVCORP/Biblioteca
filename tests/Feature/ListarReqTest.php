<?php

use App\Models\Livro;
use App\Models\Requisicao;
use App\Models\User;
use function Pest\Laravel\actingAs;

it('Garante que um utilizador consegue ver as suas requisições', function () {

    $users = User::factory(2)->create();
    $books = Livro::factory(4)->create();

    foreach ($users as $u) {
        for ($i = 0; $i < 2; $i++) {
            $data = [
                'livro_id' => $books[$i + ($u->id - 1)]->id
            ];
        
            actingAs($u)->post('/requisicoes', $data)->assertRedirect();
        }
    }

    actingAs($users[0])
        ->get(route('requisicoes.index'))
        ->assertOk()
        ->assertSeeInOrder(['REQ-0001','REQ-0002'])
        ->assertDontSee(['REQ-0003']);
});


