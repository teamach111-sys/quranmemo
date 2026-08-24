<?php

namespace Database\Factories;

use App\Models\Programme;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgrammeFactory extends Factory
{
    protected $model = Programme::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->randomElement([
                'Licence Informatique',
                'Master Génie Logiciel',
                'Diplôme Universitaire Technologique',
                'BTS Systèmes Informatiques',
                'Licence Gestion',
                'Master Administration',
                'Licence Sciences',
                'Master Mathématiques',
            ]),
            'description' => $this->faker->sentence(10),
        ];
    }
}
