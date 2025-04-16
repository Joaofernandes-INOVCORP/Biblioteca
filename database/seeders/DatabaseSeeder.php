<?php

namespace Database\Seeders;

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
    }
}
