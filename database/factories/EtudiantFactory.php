<?php

namespace Database\Factories;

use App\Models\Etudiant;
use App\Models\AnneeScolaire;
use App\Models\Groupe;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

class EtudiantFactory extends Factory
{
    protected $model = Etudiant::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'annee_scolaire_id' => AnneeScolaire::factory(),
            'groupe_id' => Groupe::factory(),
            'promotion_id' => Promotion::factory(),
            'photo' => $this->faker->imageUrl(200, 200, 'people'),
            'sexe' => $this->faker->randomElement(['M', 'F']),
            'date_naissance' => $this->faker->dateTimeBetween('-30 years', '-15 years')->format('Y-m-d'),
            'telephone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'adresse' => $this->faker->address(),
            'parent_nom' => $this->faker->lastName(),
            'parent_telephone' => $this->faker->phoneNumber(),
            'parent_relation' => $this->faker->randomElement(['Père', 'Mère', 'Tuteur']),
            'est_actif' => true,
        ];
    }
}
