<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnneeScolaire;
use App\Models\Salle;
use App\Models\Programme;
use App\Models\Niveau;
use App\Models\Matiere;
use App\Models\Promotion;
use App\Models\Classe;
use App\Models\User;

class sa extends Seeder
{
    /**
     * Seed programmes, niveaux, matieres, salles, annee scolaire, promotions, classes, and etudiants.
     */
    public function run(): void
    {
        $data = [
            [
                'nom' => 'Développement Digital option Web Full Stack',
                'niveau' => 'Technicien Spécialisé',
                'duree_annees' => 2,
                'matieres' => [
                    1 => [
                        'Algorithmique et Logique de Programmation',
                        'Programmation Orientée Objet (POO)',
                        'Conception de Sites Web Statiques (HTML/CSS)',
                        'Programmation JavaScript Client-side',
                        'Bases de Données Relationnelles et SQL',
                        'Soft Skills et Anglais Technique'
                    ],
                    2 => [
                        'Développement Back-end (PHP/Laravel ou Node.js)',
                        'Développement Front-end Avancé (React ou Vue.js)',
                        'Déploiement d\'Applications et Pratiques DevOps',
                        'Méthodologies Agiles et Gestion de Projet (Scrum)',
                        'Sécurité des Applications Web',
                        'Projet de Fin d\'Etudes & Stage'
                    ]
                ]
            ],
            [
                'nom' => 'Infrastructure Digitale option Systèmes et Réseaux',
                'niveau' => 'Technicien Spécialisé',
                'duree_annees' => 2,
                'matieres' => [
                    1 => [
                        'Architecture et Fonctionnement des Systèmes d\'Exploitation',
                        'Bases des Réseaux Informatiques (Modèle OSI, TCP/IP)',
                        'Configuration des Commutateurs et Routeurs',
                        'Automatisation des Tâches d\'Administration (Scripting Bash/Python)',
                        'Sécurité Initiale des Systèmes d\'Information',
                        'Culture et Techniques du Numérique'
                    ],
                    2 => [
                        'Administration Réseau Sous Linux et Windows Server',
                        'Services Réseaux Avancés (DNS, DHCP, Active Directory)',
                        'Concepts et Architecture du Cloud Computing',
                        'Sécurisation des Infrastructures Réseaux (Firewalls, VPN)',
                        'Supervision et Monitoring de Parcs Informatiques',
                        'Stage Pratique en Entreprise'
                    ]
                ]
            ],
            [
                'nom' => 'Gestion des Entreprises option Comptabilité et Finance',
                'niveau' => 'Technicien Spécialisé',
                'duree_annees' => 2,
                'matieres' => [
                    1 => [
                        'Comptabilité Générale (Bases et Opérations Courantes)',
                        'Économie Générale et Statistique',
                        'Droit des Affaires et Droit du Travail',
                        'Outils Bureautiques et Tableurs Spécialisés (Excel)',
                        'Mathématiques Financières',
                        'Communication Professionnelle'
                    ],
                    2 => [
                        'Comptabilité Analytique d\'Exploitation',
                        'Gestion Budgétaire et Prévisionnelle',
                        'Fiscalité de l\'Entreprise (TVA, IS, IR)',
                        'Analyse Financière et Diagnostic',
                        'Logiciels de Gestion Comptable Intégrés (Sage)',
                        'Projet Professionnel Appliqué'
                    ]
                ]
            ],
            [
                'nom' => 'Diagnostic et Electronique Embarquée Automobile',
                'niveau' => 'Technicien Spécialisé',
                'duree_annees' => 2,
                'matieres' => [
                    1 => [
                        'Principes Fondamentaux de la Mécanique Automobile',
                        'Électricité et Électronique Automobile de Base',
                        'Lecture et Interprétation de Schémas Électriques',
                        'Systèmes de Freinage et Liaison au Sol',
                        'Hygiène, Sécurité et Protection de l\'Environnement (HSE)',
                        'Informatique Appliquée'
                    ],
                    2 => [
                        'Systèmes d\'Injection Électronique (Essence et Diesel)',
                        'Utilisation des Appareils de Diagnostic (Valise de test)',
                        'Multiplexage et Calculateurs Embarqués',
                        'Systèmes de Sécurité Active et Confort (ABS, ESP, Airbags)',
                        'Gestion d\'un Atelier de Réparation Automobile',
                        'Stage Technique en Concessionnaire'
                    ]
                ]
            ],
            [
                'nom' => 'Technicien Comptable d\'Entreprises',
                'niveau' => 'Technicien',
                'duree_annees' => 2,
                'matieres' => [
                    1 => [
                        'Introduction à l\'Organisation des Entreprises',
                        'Concepts Fondamentaux de la Comptabilité',
                        'Traitement des Pièces Comptables (Facturation)',
                        'Secrétariat et Correspondance Commerciale',
                        'Calculs Commerciaux de Base',
                        'Arabe Professionnel'
                    ],
                    2 => [
                        'Travaux d\'Inventaire et Clôture d\'Exercice',
                        'Déclarations Fiscales Courantes',
                        'Traitement Informatique de la Comptabilité',
                        'Gestion de la Paie (Salaires et Cotisations CNSS)',
                        'Archivage et Gestion Documentaire',
                        'Stage de Fin de Formation'
                    ]
                ]
            ]
        ];

        // ── 1. Seed Active Année Scolaire ──
        $annee = AnneeScolaire::create([
            'libelle' => '2025-2026',
            'date_debut' => '2025-09-01',
            'date_fin' => '2026-07-31',
            'est_en_cours' => true,
        ]);

        // ── 2. Seed Salles ──
        $salle1 = Salle::create(['nom' => '1', 'capacite' => 35]);
        $salle2 = Salle::create(['nom' => '2', 'capacite' => 35]);

        // ── 3. Seed Professeurs ──
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

        // ── 4. Seed Programmes, Niveaux, Matieres, Promotions & Classes ──
        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];

        foreach ($data as $item) {
            $programme = Programme::create([
                'nom' => $item['nom'],
            ]);

            $niveau = Niveau::create([
                'nom' => $item['niveau'],
                'programme_id' => $programme->id,
                'nombre_annees' => $item['duree_annees'],
            ]);

            foreach ($item['matieres'] as $anneeEtude => $matieres) {
                $matiereModels = [];
                foreach ($matieres as $matiereNom) {
                    $matiereModels[] = Matiere::create([
                        'nom' => $matiereNom,
                        'niveau_id' => $niveau->id,
                        'annee_etude' => $anneeEtude,
                    ]);
                }

                // Create a Promotion for this programme/year combo
                $promotion = Promotion::create([
                    'annee_scolaire_id' => $annee->id,
                    'programme_id' => $programme->id,
                    'niveau_id' => $niveau->id,
                    'annee_etude' => $anneeEtude,
                ]);

                // Create Classes: groupe soir, 6 days × 2 slots
                // Salle 1 → 15:00-16:30, Salle 2 → 16:30-18:00
                // Each slot gets a different matiere (cycling through available matieres)
                $matiereIndex = 0;
                $totalMatieres = count($matiereModels);

                foreach ($jours as $jour) {
                    // Classe 1: 15:00 - 16:30 in salle 1
                    $prof1 = $professeurs[array_rand($professeurs)];
                    Classe::create([
                        'promotion_id' => $promotion->id,
                        'matiere_id' => $matiereModels[$matiereIndex % $totalMatieres]->id,
                        'professeur_id' => $prof1->id,
                        'groupe' => 'soir',
                        'salle' => $salle1->nom,
                        'jour' => $jour,
                        'heure_debut' => '15:00',
                        'heure_fin' => '16:30',
                    ]);
                    $matiereIndex++;

                    // Classe 2: 16:30 - 18:00 in salle 2
                    $prof2 = $professeurs[array_rand($professeurs)];
                    Classe::create([
                        'promotion_id' => $promotion->id,
                        'matiere_id' => $matiereModels[$matiereIndex % $totalMatieres]->id,
                        'professeur_id' => $prof2->id,
                        'groupe' => 'soir',
                        'salle' => $salle2->nom,
                        'jour' => $jour,
                        'heure_debut' => '16:30',
                        'heure_fin' => '18:00',
                    ]);
                    $matiereIndex++;
                }
            }
        }

        // Seed Etudiants with random Moroccan data
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

        for ($i = 0; $i < 30; $i++) {
            $sexe = fake()->randomElement(['M', 'F']);

            if ($sexe === 'M') {
                $prenom = fake()->randomElement($prenomsMasculins);
            } else {
                $prenom = fake()->randomElement($prenomsFeminins);
            }

            \App\Models\Etudiant::create([
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
            ]);
        }
    }
}
