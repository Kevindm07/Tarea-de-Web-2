<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\model\Estudiante;
use \App\Models\Paralelo;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Estudiante>
 */
class EstudianteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Estudiante::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'ci' => $this->faker->unique()->numerify('##########'),
            'correo' => $this->faker->unique()->safeEmail(),
            'paralelos_id' => Paralelo::factory(),
        ];
    }
}
