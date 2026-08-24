<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Groupe;
use Illuminate\Support\Facades\DB;
use App\Models\Matiere;
use App\Models\Niveau;
use App\Models\Periode;
use App\Models\Programme;
use App\Models\Promotion;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with everything needed.
     */
    public function run(): void
    {
        // ╔══════════════════════════════════════════════════════════════╗
        // ║  1. ADMIN & PROFESSEUR ACCOUNTS                            ║
        // ╚══════════════════════════════════════════════════════════════╝

        User::create([
            'name' => 'muslim',
            'email' => 'admin@muslim.com',
            'password' => bcrypt('GsuDNt5E.bk:R4Q'),
            'role' => 'administrateur',
        ]);

        User::create([
            'name' => 'muslim2',
            'email' => 'admin2@muslim.com',
            'password' => bcrypt('GsuDNt5E.bk:R4Q'),
            'role' => 'professeur',
        ]);

        $professeurNames = [
            'Ahmed El Amrani', 'Fatima Benali', 'Rachid Tazi',
            'Khadija El Idrissi', 'Omar Chraibi', 'Samira Hajji',
            'Mehdi Lahlou', 'Nour Bennani',
        ];

        $professeurs = [];
        foreach ($professeurNames as $i => $name) {
            $professeurs[] = User::create([
                'name' => $name,
                'email' => 'prof' . ($i + 1) . '@memoq.test',
                'password' => bcrypt('password'),
                'role' => 'professeur',
            ]);
        }

        // ╔══════════════════════════════════════════════════════════════╗
        // ║  2. ANNÉE SCOLAIRE (single active year)                     ║
        // ╚══════════════════════════════════════════════════════════════╝

        $annee = AnneeScolaire::create([
            'libelle' => '2025-2026',
            'date_debut' => '2025-09-01',
            'date_fin' => '2026-07-31',
            'est_en_cours' => true,
        ]);

        // ╔══════════════════════════════════════════════════════════════╗
        // ║  3. PÉRIODES                                                 ║
        // ╚══════════════════════════════════════════════════════════════╝

        $periode1 = Periode::create(['nom' => 'Semestre 1']);
        $periode2 = Periode::create(['nom' => 'Semestre 2']);

        // ╔══════════════════════════════════════════════════════════════╗
        // ║  4. SALLES                                                  ║
        // ╚══════════════════════════════════════════════════════════════╝

        $salle1 = Salle::create(['nom' => '1', 'capacite' => 35]);
        $salle2 = Salle::create(['nom' => '2', 'capacite' => 35]);

        // ╔══════════════════════════════════════════════════════════════╗
        // ║  5. GROUPES                                                 ║
        // ╚══════════════════════════════════════════════════════════════╝

        $groupeNames = ['G1', 'G2', 'G3'];
        $groupes = [];
        foreach ($groupeNames as $gName) {
            $groupes[] = Groupe::create(['nom' => $gName, 'annee_scolaire_id' => $annee->id]);
        }

        // ╔══════════════════════════════════════════════════════════════╗
        // ║  6. PROGRAMMES, NIVEAUX, MATIÈRES                           ║
        // ╚══════════════════════════════════════════════════════════════╝

        $data = [
            [
                'nom' => 'Développement Digital option Web Full Stack',
                'niveau' => 'Technicien Spécialisé',
                'duree_annees' => 2,
                'nombre_periodes' => 2,
                'matieres' => [
                    1 => [
                        'Algorithmique et Logique de Programmation',
                        'Programmation Orientée Objet (POO)',
                        'Conception de Sites Web Statiques (HTML/CSS)',
                        'Programmation JavaScript Client-side',
                        'Bases de Données Relationnelles et SQL',
                        'Soft Skills et Anglais Technique',
                    ],
                    2 => [
                        'Développement Back-end (PHP/Laravel ou Node.js)',
                        'Développement Front-end Avancé (React ou Vue.js)',
                        'Déploiement d\'Applications et Pratiques DevOps',
                        'Méthodologies Agiles et Gestion de Projet (Scrum)',
                        'Sécurité des Applications Web',
                        'Projet de Fin d\'Etudes & Stage',
                    ],
                ],
            ],
            [
                'nom' => 'Infrastructure Digitale option Systèmes et Réseaux',
                'niveau' => 'Technicien Spécialisé',
                'duree_annees' => 2,
                'nombre_periodes' => 2,
                'matieres' => [
                    1 => [
                        'Architecture et Fonctionnement des Systèmes d\'Exploitation',
                        'Bases des Réseaux Informatiques (Modèle OSI, TCP/IP)',
                        'Configuration des Commutateurs et Routeurs',
                        'Automatisation des Tâches d\'Administration (Scripting Bash/Python)',
                        'Sécurité Initiale des Systèmes d\'Information',
                        'Culture et Techniques du Numérique',
                    ],
                    2 => [
                        'Administration Réseau Sous Linux et Windows Server',
                        'Services Réseaux Avancés (DNS, DHCP, Active Directory)',
                        'Concepts et Architecture du Cloud Computing',
                        'Sécurisation des Infrastructures Réseaux (Firewalls, VPN)',
                        'Supervision et Monitoring de Parcs Informatiques',
                        'Stage Pratique en Entreprise',
                    ],
                ],
            ],
            [
                'nom' => 'Gestion des Entreprises option Comptabilité et Finance',
                'niveau' => 'Technicien Spécialisé',
                'duree_annees' => 2,
                'nombre_periodes' => 2,
                'matieres' => [
                    1 => [
                        'Comptabilité Générale (Bases et Opérations Courantes)',
                        'Économie Générale et Statistique',
                        'Droit des Affaires et Droit du Travail',
                        'Outils Bureautiques et Tableurs Spécialisés (Excel)',
                        'Mathématiques Financières',
                        'Communication Professionnelle',
                    ],
                    2 => [
                        'Comptabilité Analytique d\'Exploitation',
                        'Gestion Budgétaire et Prévisionnelle',
                        'Fiscalité de l\'Entreprise (TVA, IS, IR)',
                        'Analyse Financière et Diagnostic',
                        'Logiciels de Gestion Comptable Intégrés (Sage)',
                        'Projet Professionnel Appliqué',
                    ],
                ],
            ],
            [
                'nom' => 'Diagnostic et Electronique Embarquée Automobile',
                'niveau' => 'Technicien Spécialisé',
                'duree_annees' => 2,
                'nombre_periodes' => 2,
                'matieres' => [
                    1 => [
                        'Principes Fondamentaux de la Mécanique Automobile',
                        'Électricité et Électronique Automobile de Base',
                        'Lecture et Interprétation de Schémas Électriques',
                        'Systèmes de Freinage et Liaison au Sol',
                        'Hygiène, Sécurité et Protection de l\'Environnement (HSE)',
                        'Informatique Appliquée',
                    ],
                    2 => [
                        'Systèmes d\'Injection Électronique (Essence et Diesel)',
                        'Utilisation des Appareils de Diagnostic (Valise de test)',
                        'Multiplexage et Calculateurs Embarqués',
                        'Systèmes de Sécurité Active et Confort (ABS, ESP, Airbags)',
                        'Gestion d\'un Atelier de Réparation Automobile',
                        'Stage Technique en Concessionnaire',
                    ],
                ],
            ],
            [
                'nom' => 'Technicien Comptable d\'Entreprises',
                'niveau' => 'Technicien',
                'duree_annees' => 2,
                'nombre_periodes' => 2,
                'matieres' => [
                    1 => [
                        'Introduction à l\'Organisation des Entreprises',
                        'Concepts Fondamentaux de la Comptabilité',
                        'Traitement des Pièces Comptables (Facturation)',
                        'Secrétariat et Correspondance Commerciale',
                        'Calculs Commerciaux de Base',
                        'Arabe Professionnel',
                    ],
                    2 => [
                        'Travaux d\'Inventaire et Clôture d\'Exercice',
                        'Déclarations Fiscales Courantes',
                        'Traitement Informatique de la Comptabilité',
                        'Gestion de la Paie (Salaires et Cotisations CNSS)',
                        'Archivage et Gestion Documentaire',
                        'Stage de Fin de Formation',
                    ],
                ],
            ],
        ];

        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
        $salles = [$salle1, $salle2];
        $allPromotions = [];

        // Time slots: 2 classes per day per groupe
        $timeSlots = [
            ['heure_debut' => '08:30', 'heure_fin' => '10:30'],
            ['heure_debut' => '10:30', 'heure_fin' => '12:30'],
        ];

        foreach ($data as $item) {
            $programme = Programme::create([
                'nom' => $item['nom'],
            ]);

            $niveau = Niveau::create([
                'nom' => $item['niveau'],
                'programme_id' => $programme->id,
                'nombre_annees' => $item['duree_annees'],
                'nombre_periodes' => $item['nombre_periodes'],
            ]);

            foreach ($item['matieres'] as $anneeEtude => $matieres) {
                $matiereModels = [];
                $mIndex = 0;
                foreach ($matieres as $matiereNom) {
                    $matiereModels[] = Matiere::create([
                        'nom' => $matiereNom,
                        'niveau_id' => $niveau->id,
                        'annee_etude' => $anneeEtude,
                        'periode_id' => $mIndex < 3 ? $periode1->id : $periode2->id,
                    ]);
                    $mIndex++;
                }

                // Create a Promotion for this programme/year combo
                $promotion = Promotion::create([
                    'annee_scolaire_id' => $annee->id,
                    'programme_id' => $programme->id,
                    'niveau_id' => $niveau->id,
                    'annee_etude' => $anneeEtude,
                ]);

                $allPromotions[] = [
                    'promotion' => $promotion,
                    'matiereModels' => $matiereModels,
                ];

                // ── CLASSES: for each groupe → 7 days × 2 slots/day = 14 classes ──
                $totalMatieres = count($matiereModels);

                foreach ($groupes as $gIndex => $groupe) {
                    $matiereIndex = 0;

                    foreach ($jours as $jour) {
                        foreach ($timeSlots as $slotIndex => $slot) {
                            $prof = $professeurs[array_rand($professeurs)];
                            $salle = $salles[($gIndex + $slotIndex) % count($salles)];
                            $selectedMatiere = $matiereModels[$matiereIndex % $totalMatieres];

                            Classe::create([
                                'promotion_id' => $promotion->id,
                                'matiere_id' => $selectedMatiere->id,
                                'professeur_id' => $prof->id,
                                'groupe_id' => $groupe->id,
                                'salle' => $salle->nom,
                                'jour' => $jour,
                                'heure_debut' => $slot['heure_debut'],
                                'heure_fin' => $slot['heure_fin'],
                            ]);
                            $matiereIndex++;
                        }
                    }
                }
            }
        }

        // ╔══════════════════════════════════════════════════════════════╗
        // ║  7. ÉTUDIANTS                                               ║
        // ╚══════════════════════════════════════════════════════════════╝

        $prenomsMasculins = [
            'Youssef', 'Mohammed', 'Adam', 'Amine', 'Hamza',
            'Omar', 'Ayoub', 'Zakaria', 'Ibrahim', 'Mehdi',
            'Khalid', 'Rachid', 'Samir', 'Hassan', 'Bilal',
        ];

        $prenomsFeminins = [
            'Fatima Zahra', 'Khadija', 'Meryem', 'Salma', 'Hajar',
            'Amina', 'Sara', 'Nour', 'Imane', 'Zineb',
            'Laila', 'Yasmine', 'Houda', 'Samira', 'Nawal',
        ];

        $noms = [
            'El Amrani', 'Benali', 'Tazi', 'El Idrissi', 'Berrada',
            'Chraibi', 'El Fassi', 'Hajji', 'Lahlou', 'Bennani',
            'Alaoui', 'El Mansouri', 'Bouzid', 'Kettani', 'Sqalli',
            'Cherkaoui', 'El Ouazzani', 'Zouak', 'Naciri', 'Bouazza',
        ];

        $adresses = [
            'Hay Riad, Rabat', 'Maârif, Casablanca', 'Guéliz, Marrakech',
            'Ville Nouvelle, Fès', 'Quartier Administratif, Agadir',
            'Hay Hassani, Casablanca', 'Océan, Rabat', 'Hay Mohammadi, Kénitra',
            'Centre Ville, Tanger', 'Hay Salam, Meknès',
            'Sbata, Casablanca', 'Agdal, Rabat', 'Hivernage, Marrakech',
            'Saïss, Fès', 'Talborjt, Agadir',
        ];

        $relations = ['Père', 'Mère', 'Tuteur', 'Oncle', 'Tante'];

        // Distribute students across promotions and groupes
        $etudiants = [];
        foreach ($allPromotions as $promoData) {
            foreach ($groupes as $groupe) {
                for ($i = 0; $i < 20; $i++) {
                    $sexe = fake()->randomElement(['M', 'F']);
                    $prenom = $sexe === 'M'
                        ? fake()->randomElement($prenomsMasculins)
                        : fake()->randomElement($prenomsFeminins);

                    $etudiant = Etudiant::create([
                        'nom' => fake()->randomElement($noms),
                        'prenom' => $prenom,
                        'sexe' => $sexe,
                        'date_naissance' => fake()->dateTimeBetween('2000-01-01', '2008-12-31')->format('Y-m-d'),
                        'telephone' => '06' . fake()->numerify('########'),
                        'email' => fake()->optional(0.7)->safeEmail(),
                        'adresse' => fake()->randomElement($adresses),
                        'parent_nom' => fake()->randomElement($noms) . ' ' . fake()->randomElement($prenomsMasculins),
                        'parent_telephone' => '06' . fake()->numerify('########'),
                        'parent_relation' => fake()->randomElement($relations),
                        'est_actif' => fake()->boolean(85),
                        'annee_scolaire_id' => $annee->id,
                        'groupe_id' => $groupe->id,
                        'promotion_id' => $promoData['promotion']->id,
                    ]);

                    $etudiants[] = [
                        'etudiant' => $etudiant,
                        'promoData' => $promoData,
                        'groupe' => $groupe,
                    ];
                }
            }
        }

        // ╔══════════════════════════════════════════════════════════════╗
        // ║  8. CLASSE ↔ ETUDIANT PIVOT (enroll students in classes)    ║
        // ╚══════════════════════════════════════════════════════════════╝

        foreach ($etudiants as $entry) {
            $etudiant = $entry['etudiant'];
            $promotion = $entry['promoData']['promotion'];
            $groupe = $entry['groupe'];

            // Enroll student only in classes matching their promotion AND groupe
            $classeIds = Classe::where('promotion_id', $promotion->id)
                ->where('groupe_id', $groupe->id)
                ->pluck('id');

            foreach ($classeIds as $classeId) {
                DB::table('classe_etudiant')->insert([
                    'etudiant_id' => $etudiant->id,
                    'classe_id' => $classeId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
