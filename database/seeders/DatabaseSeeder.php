<?php

namespace Database\Seeders;

use App\Models\Requisicao;
use App\Models\User;
use App\Models\Autor;
use App\Models\Editora;
use App\Models\Livro;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        User::factory()->create([
            'name' => 'TESTER',
            'email' => 'test@mail.com',
            'password' => bcrypt('123456'),
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'TESTER_REQ',
            'email' => 'test2@mail.com',
            'password' => bcrypt('123456'),
        ]);

        User::factory(10)->create();

        $editoras = Editora::factory(5)->create();

        $autores = Autor::factory(10)->create();

        Livro::factory(20)->make()->each(function ($livro) use ($editoras, $autores) {
            $livro->editora_id = $editoras->random()->id;
            $livro->save();

            $livro->autores()->attach(
                $autores->random(rand(1, 3))->pluck('id')->toArray()
            );
        });

        //vai buscar os 2 primeiros users, 5 livros aleatórios e proximo ID de requisições
        $users = User::limit(2)->get();
        $books = Livro::where('stock', '>=', 2)->inRandomOrder()->limit(5)->get();
        $curr = (Requisicao::max('id') ?? 0) + 1;

        foreach ($users as $u) {
            for ($i = 0; $i < 2; $i++) {
                $remove = random_int(3, 4);

                $r = Requisicao::create([
                    'numero' => 'REQ-' . str_pad($curr, 4, '0', STR_PAD_LEFT),
                    'livro_id' => $books[$i + ($u->id - 1)]->id,
                    'user_id' => $u->id,
                    'data_inicio' => now()->subDays($remove)->toDateString(),
                    'data_prevista_fim' => now()->subDays($remove)->addDays(5)->toDateString(),
                    'foto_cidadao' => "",
                ]);

                $r->save();

                $curr++;
            }
        }

        $r = Requisicao::create([
            'numero' => 'REQ-' . str_pad($curr, 4, '0', STR_PAD_LEFT),
            'livro_id' => $books[4]->id,
            'user_id' => $users[1]->id,
            'data_inicio' => now()->subDays($remove)->toDateString(),
            'data_prevista_fim' => now()->subDays($remove)->addDays(5)->toDateString(),
            'foto_cidadao' => "",
        ]);

        $r->save();

    }
}
