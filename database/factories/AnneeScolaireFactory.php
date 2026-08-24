<?php

namespace Database\Factories;

use App\Models\AnneeScolaire;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnneeScolaireFactory extends Factory
{
    protected $model = AnneeScolaire::class;

    public function definition(): array
    {
        $dateDebut = $this->faker->dateTimeBetween('-3 years', 'now');
        $dateFin = $this->faker->dateTimeBetween($dateDebut, '+1 year');

        return [
            'libelle' => $dateDebut->format('Y') . '-' . $dateFin->format('Y'),
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'est_en_cours' => false,
        ];
    }

    public function enCours()
    {
        return $this->state([
            'est_en_cours' => true,
        ]);
    }
}
