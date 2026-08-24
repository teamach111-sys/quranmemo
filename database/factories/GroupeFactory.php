<?php

namespace Database\Factories;

use App\Models\Groupe;
use App\Models\AnneeScolaire;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupeFactory extends Factory
{
    protected $model = Groupe::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']) .
                $this->faker->numberBetween(1, 5),
            'annee_scolaire_id' => AnneeScolaire::factory(),
        ];
    }
}
