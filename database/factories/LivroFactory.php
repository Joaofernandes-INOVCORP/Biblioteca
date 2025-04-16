<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Livro>
 */
class LivroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'isbn' => $this->faker->isbn13(),
            'nome' => $this->faker->sentence(3),
            'bibliografia' => $this->faker->paragraph(),
            'capa' => null,
            'preco' => $this->faker->randomFloat(2, 5, 50),
            'editora_id' => \App\Models\Editora::inRandomOrder()->first()?->id ?? \App\Models\Editora::factory(),
        ];
    }
}
