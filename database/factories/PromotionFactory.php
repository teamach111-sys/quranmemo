<?php

namespace Database\Factories;

use App\Models\Promotion;
use App\Models\AnneeScolaire;
use App\Models\Programme;
use App\Models\Niveau;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionFactory extends Factory
{
    protected $model = Promotion::class;

    public function definition(): array
    {
        return [
            'annee_scolaire_id' => AnneeScolaire::factory(),
            'programme_id' => Programme::factory(),
            'niveau_id' => Niveau::factory(),
            'annee_etude' => $this->faker->numberBetween(1, 6),
        ];
    }
}
