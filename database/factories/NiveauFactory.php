<?php

namespace Database\Factories;

use App\Models\Niveau;
use App\Models\Programme;
use Illuminate\Database\Eloquent\Factories\Factory;

class NiveauFactory extends Factory
{
    protected $model = Niveau::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->randomElement([
                'Licence 1',
                'Licence 2',
                'Licence 3',
                'Master 1',
                'Master 2',
                'BTS 1',
                'BTS 2',
            ]),
            'description' => $this->faker->sentence(),
            'nombre_periodes' => $this->faker->numberBetween(2, 4),
            'programme_id' => Programme::factory(),
            'nombre_annees' => $this->faker->numberBetween(1, 4),
        ];
    }
}
