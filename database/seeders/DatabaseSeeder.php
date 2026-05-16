<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Machine;
use App\Models\Reservation;
use App\Models\Rating;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ═══════════════════════════════════════════════════
        // 1. ADMIN
        // ═══════════════════════════════════════════════════
        $admin = User::create([
            'name'         => 'Admin Rentify',
            'email'        => 'admin@rentify.ma',
            'password'     => Hash::make('password'),
            'role'         => 'admin',
            'is_suspended' => false,
        ]);

        // ═══════════════════════════════════════════════════
        // 2. OWNERS (propriétaires)
        // ═══════════════════════════════════════════════════
        $owners = [
            ['name' => 'Karim Bennani',   'email' => 'karim@rentify.ma',   'city' => 'Casablanca'],
            ['name' => 'Youssef Alami',   'email' => 'youssef@rentify.ma', 'city' => 'Rabat'],
            ['name' => 'Hassan Tazi',     'email' => 'hassan@rentify.ma',  'city' => 'Tanger'],
            ['name' => 'Omar Chraibi',    'email' => 'omar@rentify.ma',    'city' => 'Fès'],
            ['name' => 'Rachid Moussaoui','email' => 'rachid@rentify.ma',  'city' => 'Marrakech'],
        ];

        $ownerModels = [];
        foreach ($owners as $o) {
            $ownerModels[] = User::create([
                'name'         => $o['name'],
                'email'        => $o['email'],
                'password'     => Hash::make('password'),
                'role'         => 'owner',
                'is_suspended' => false,
            ]);
        }

        // ═══════════════════════════════════════════════════
        // 3. CLIENTS
        // ═══════════════════════════════════════════════════
        $clients = [
            ['name' => 'Fadwa El Idrissi', 'email' => 'fadwa@rentify.ma'],
            ['name' => 'Mehdi Ouali',      'email' => 'mehdi@rentify.ma'],
            ['name' => 'Sara Benhaddou',   'email' => 'sara@rentify.ma'],
            ['name' => 'Amine Ziani',      'email' => 'amine@rentify.ma'],
            ['name' => 'Nadia Filali',     'email' => 'nadia@rentify.ma'],
            ['name' => 'Tariq Belkadi',    'email' => 'tariq@rentify.ma'],
        ];

        $clientModels = [];
        foreach ($clients as $c) {
            $clientModels[] = User::create([
                'name'         => $c['name'],
                'email'        => $c['email'],
                'password'     => Hash::make('password'),
                'role'         => 'client',
                'is_suspended' => false,
            ]);
        }

        // ═══════════════════════════════════════════════════
        // 4. MACHINES
        // ═══════════════════════════════════════════════════
        $machines = [
            // Casablanca — owner[0]
            [
                'owner_id'    => $ownerModels[0]->id,
                'name'        => 'Pelleteuse Caterpillar 320',
                'category'    => 'Excavation',
                'description' => 'Pelleteuse hydraulique 20 tonnes, idéale pour chantiers urbains.',
                'daily_price' => 1800,
                'status'      => 'available',
                'city'        => 'Casablanca',
                'latitude'    => 33.5731,
                'longitude'   => -7.5898,
            ],
            [
                'owner_id'    => $ownerModels[0]->id,
                'name'        => 'Grue Mobile Liebherr LTM 1050',
                'category'    => 'Levage',
                'description' => 'Grue mobile 50 tonnes, boom télescopique 38m.',
                'daily_price' => 3500,
                'status'      => 'rented',
                'city'        => 'Casablanca',
                'latitude'    => 33.5950,
                'longitude'   => -7.6192,
            ],
            // Rabat — owner[1]
            [
                'owner_id'    => $ownerModels[1]->id,
                'name'        => 'Bulldozer Komatsu D65',
                'category'    => 'Terrassement',
                'description' => 'Bulldozer puissant pour travaux de terrassement et nivellement.',
                'daily_price' => 2200,
                'status'      => 'available',
                'city'        => 'Rabat',
                'latitude'    => 34.0209,
                'longitude'   => -6.8416,
            ],
            [
                'owner_id'    => $ownerModels[1]->id,
                'name'        => 'Compacteur Hamm HD 90',
                'category'    => 'Compactage',
                'description' => 'Rouleau compresseur double bille, parfait pour voirie.',
                'daily_price' => 950,
                'status'      => 'available',
                'city'        => 'Rabat',
                'latitude'    => 34.0132,
                'longitude'   => -6.8326,
            ],
            // Tanger — owner[2]
            [
                'owner_id'    => $ownerModels[2]->id,
                'name'        => 'Chargeuse JCB 437',
                'category'    => 'Manutention',
                'description' => 'Chargeuse sur pneus 4x4, grande capacité de godet.',
                'daily_price' => 1400,
                'status'      => 'available',
                'city'        => 'Tanger',
                'latitude'    => 35.7595,
                'longitude'   => -5.8340,
            ],
            [
                'owner_id'    => $ownerModels[2]->id,
                'name'        => 'Bétonnière Putzmeister 52m',
                'category'    => 'Béton',
                'description' => 'Pompe à béton sur camion, flèche 52 mètres.',
                'daily_price' => 2800,
                'status'      => 'maintenance',
                'city'        => 'Tanger',
                'latitude'    => 35.7680,
                'longitude'   => -5.7995,
            ],
            // Fès — owner[3]
            [
                'owner_id'    => $ownerModels[3]->id,
                'name'        => 'Mini-pelle Kubota KX080',
                'category'    => 'Excavation',
                'description' => 'Mini-pelle compacte 8 tonnes, accès espaces réduits.',
                'daily_price' => 850,
                'status'      => 'available',
                'city'        => 'Fès',
                'latitude'    => 34.0181,
                'longitude'   => -5.0078,
            ],
            [
                'owner_id'    => $ownerModels[3]->id,
                'name'        => 'Nacelle Haulotte 28m',
                'category'    => 'Élévation',
                'description' => 'Nacelle télescopique 28m, plateau rotatif 360°.',
                'daily_price' => 1200,
                'status'      => 'available',
                'city'        => 'Fès',
                'latitude'    => 34.0372,
                'longitude'   => -4.9998,
            ],
            // Marrakech — owner[4]
            [
                'owner_id'    => $ownerModels[4]->id,
                'name'        => 'Tombereau Volvo A25G',
                'category'    => 'Transport',
                'description' => 'Tombereau articulé 6x6, capacité 24 tonnes.',
                'daily_price' => 2600,
                'status'      => 'available',
                'city'        => 'Marrakech',
                'latitude'    => 31.6295,
                'longitude'   => -7.9811,
            ],
            [
                'owner_id'    => $ownerModels[4]->id,
                'name'        => 'Foreuse Atlas Copco ROC D7',
                'category'    => 'Forage',
                'description' => 'Foreuse hydraulique sur chenilles pour roches dures.',
                'daily_price' => 3200,
                'status'      => 'rented',
                'city'        => 'Marrakech',
                'latitude'    => 31.6085,
                'longitude'   => -8.0082,
            ],
        ];

        $machineModels = [];
        foreach ($machines as $m) {
            $machineModels[] = Machine::create($m);
        }

        // ═══════════════════════════════════════════════════
        // 5. RÉSERVATIONS
        // ═══════════════════════════════════════════════════
        $reservations = [
            // Complétées
            [
                'machine_id' => $machineModels[0]->id,
                'user_id'    => $clientModels[0]->id,
                'start_date' => now()->subDays(40),
                'end_date'   => now()->subDays(35),
                'status'     => 'completed',
                'motif'      => 'Travaux de fondation immeuble R+5',
            ],
            [
                'machine_id' => $machineModels[2]->id,
                'user_id'    => $clientModels[1]->id,
                'start_date' => now()->subDays(30),
                'end_date'   => now()->subDays(25),
                'status'     => 'completed',
                'motif'      => 'Terrassement zone industrielle',
            ],
            [
                'machine_id' => $machineModels[4]->id,
                'user_id'    => $clientModels[2]->id,
                'start_date' => now()->subDays(20),
                'end_date'   => now()->subDays(17),
                'status'     => 'completed',
                'motif'      => 'Chargement matériaux carrière',
            ],
            [
                'machine_id' => $machineModels[6]->id,
                'user_id'    => $clientModels[3]->id,
                'start_date' => now()->subDays(15),
                'end_date'   => now()->subDays(12),
                'status'     => 'completed',
                'motif'      => 'Fouilles pour parking souterrain',
            ],
            [
                'machine_id' => $machineModels[8]->id,
                'user_id'    => $clientModels[4]->id,
                'start_date' => now()->subDays(10),
                'end_date'   => now()->subDays(7),
                'status'     => 'completed',
                'motif'      => 'Transport déblais chantier autoroute',
            ],
            // Acceptées
            [
                'machine_id' => $machineModels[1]->id,
                'user_id'    => $clientModels[5]->id,
                'start_date' => now()->subDays(3),
                'end_date'   => now()->addDays(2),
                'status'     => 'accepted',
                'motif'      => 'Montage structure métallique entrepôt',
            ],
            [
                'machine_id' => $machineModels[9]->id,
                'user_id'    => $clientModels[0]->id,
                'start_date' => now()->subDays(2),
                'end_date'   => now()->addDays(4),
                'status'     => 'accepted',
                'motif'      => 'Forage puits eau souterraine',
            ],
            // En attente
            [
                'machine_id' => $machineModels[3]->id,
                'user_id'    => $clientModels[1]->id,
                'start_date' => now()->addDays(2),
                'end_date'   => now()->addDays(6),
                'status'     => 'pending',
                'motif'      => 'Compactage route communale',
            ],
            [
                'machine_id' => $machineModels[7]->id,
                'user_id'    => $clientModels[2]->id,
                'start_date' => now()->addDays(1),
                'end_date'   => now()->addDays(3),
                'status'     => 'pending',
                'motif'      => 'Rénovation façade immeuble historique',
            ],
            [
                'machine_id' => $machineModels[0]->id,
                'user_id'    => $clientModels[3]->id,
                'start_date' => now()->addDays(5),
                'end_date'   => now()->addDays(8),
                'status'     => 'pending',
                'motif'      => 'Démolition ancienne usine',
            ],
            // Refusées
            [
                'machine_id' => $machineModels[5]->id,
                'user_id'    => $clientModels[4]->id,
                'start_date' => now()->subDays(8),
                'end_date'   => now()->subDays(5),
                'status'     => 'rejected',
                'motif'      => 'Coulage dalle béton',
            ],
        ];

        $reservationModels = [];
        foreach ($reservations as $r) {
            $reservationModels[] = Reservation::create($r);
        }

        // ═══════════════════════════════════════════════════
        // 6. RATINGS (pour réservations complétées)
        // ═══════════════════════════════════════════════════
        $ratings = [
            ['reservation_id' => $reservationModels[0]->id, 'machine_id' => $machineModels[0]->id, 'user_id' => $clientModels[0]->id, 'rating' => 5, 'comment' => 'Excellent matériel, livraison ponctuelle. Je recommande vivement !'],
            ['reservation_id' => $reservationModels[1]->id, 'machine_id' => $machineModels[2]->id, 'user_id' => $clientModels[1]->id, 'rating' => 4, 'comment' => 'Très bon bulldozer, opérateur professionnel. Quelques retards mineurs.'],
            ['reservation_id' => $reservationModels[2]->id, 'machine_id' => $machineModels[4]->id, 'user_id' => $clientModels[2]->id, 'rating' => 5, 'comment' => 'Parfait ! Chargeuse en excellent état, prix raisonnable.'],
            ['reservation_id' => $reservationModels[3]->id, 'machine_id' => $machineModels[6]->id, 'user_id' => $clientModels[3]->id, 'rating' => 4, 'comment' => 'Mini-pelle très maniable, idéale pour les espaces réduits.'],
            ['reservation_id' => $reservationModels[4]->id, 'machine_id' => $machineModels[8]->id, 'user_id' => $clientModels[4]->id, 'rating' => 3, 'comment' => 'Tombereau fonctionnel mais quelques problèmes mécaniques en fin de chantier.'],
        ];

        foreach ($ratings as $rating) {
            Rating::create($rating);
        }

        $this->command->info('');
        $this->command->info('✅ Rentify DB seeded successfully!');
        $this->command->info('');
        $this->command->info('👤 COMPTES DE TEST :');
        $this->command->info('   Admin   → admin@rentify.ma    / password');
        $this->command->info('   Owner   → karim@rentify.ma    / password');
        $this->command->info('   Owner   → youssef@rentify.ma  / password');
        $this->command->info('   Client  → fadwa@rentify.ma    / password');
        $this->command->info('   Client  → mehdi@rentify.ma    / password');
        $this->command->info('');
        $this->command->info('📦 DONNÉES :');
        $this->command->info('   1 admin · 5 owners · 6 clients');
        $this->command->info('   10 machines (Casablanca, Rabat, Tanger, Fès, Marrakech)');
        $this->command->info('   11 réservations (5 complétées · 2 acceptées · 3 en attente · 1 refusée)');
        $this->command->info('   5 ratings');
        $this->command->info('');
    }
}