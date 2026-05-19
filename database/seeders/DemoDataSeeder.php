<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════
        //  USERS
        // ══════════════════════════════════════
        $users = [
            [
                'name'     => 'Karim Bennani',
                'email'    => 'karim@rentify.ma',
                'password' => Hash::make('password'),
                'role'     => 'owner',
                'phone'    => '+212661234567',
                'city'     => 'Casablanca',
                'bio'      => 'Propriétaire de machines BTP depuis 10 ans.',
            ],
            [
                'name'     => 'Fadwa El Idrissi',
                'email'    => 'fadwa@rentify.ma',
                'password' => Hash::make('password'),
                'role'     => 'client',
                'phone'    => '+212620722338',
                'city'     => 'Rabat',
                'bio'      => 'Entreprise de construction.',
            ],
            [
                'name'     => 'Admin Rentify',
                'email'    => 'admin@rentify.ma',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'phone'    => '+212600000000',
                'city'     => 'Casablanca',
                'bio'      => 'Équipe Rentify.',
            ],
        ];

        foreach ($users as $u) {
            DB::table('users')->updateOrInsert(
                ['email' => $u['email']],
                array_merge($u, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        // ══════════════════════════════════════
        //  MACHINES  ← كانت ناقصة الإدخال
        // ══════════════════════════════════════
        $ownerId = DB::table('users')->where('email', 'karim@rentify.ma')->value('id');

        $machines = [
            [
                'name'           => 'JCB 3CX Backhoe Loader',
                'type'           => 'Excavatrice',
                'description'    => 'Engin polyvalent idéal pour travaux de terrassement.',
                'price_per_day'  => 2500,
                'price_per_hour' => 350,
                'status'         => 'available',
                'city'           => 'Casablanca',
                'location'       => 'Casablanca, Maroc',
                'latitude'       => 33.5731,
                'longitude'      => -7.5898,
            ],
            [
                'name'           => 'Manitou MT 1840',
                'type'           => 'Chargeuse',
                'description'    => 'Chariot télescopique grande portée.',
                'price_per_day'  => 1800,
                'price_per_hour' => 250,
                'status'         => 'available',
                'city'           => 'Rabat',
                'location'       => 'Rabat, Maroc',
                'latitude'       => 34.0209,
                'longitude'      => -6.8416,
            ],
            [
                'name'           => 'Caterpillar 320 GX',
                'type'           => 'Excavatrice',
                'description'    => 'Pelle hydraulique haute performance.',
                'price_per_day'  => 3200,
                'price_per_hour' => 450,
                'status'         => 'available',
                'city'           => 'Marrakech',
                'location'       => 'Marrakech, Maroc',
                'latitude'       => 31.6295,
                'longitude'      => -7.9811,
            ],
            [
                'name'           => 'Camion Benne Volvo FH16',
                'type'           => 'Camion',
                'description'    => 'Camion benne 8x4 pour transport de matériaux.',
                'price_per_day'  => 1500,
                'price_per_hour' => 200,
                'status'         => 'unavailable',
                'city'           => 'Fès',
                'location'       => 'Fès, Maroc',
                'latitude'       => 34.0181,
                'longitude'      => -5.0078,
            ],
            [
                'name'           => 'Compacteur Bomag BW 213',
                'type'           => 'Compacteur',
                'description'    => 'Rouleau compacteur pour routes et plateformes.',
                'price_per_day'  => 1200,
                'price_per_hour' => 170,
                'status'         => 'available',
                'city'           => 'Tanger',
                'location'       => 'Tanger, Maroc',
                'latitude'       => 35.7595,
                'longitude'      => -5.8340,
            ],
        ];

        foreach ($machines as $m) {
            DB::table('machines')->updateOrInsert(
                ['name' => $m['name']],   // ← clé unique : le nom
                array_merge($m, [
                    'owner_id'   => $ownerId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        // ══════════════════════════════════════
        //  RESERVATIONS
        // ══════════════════════════════════════
        $clientId = DB::table('users')->where('email', 'fadwa@rentify.ma')->value('id');
        $machine1 = DB::table('machines')->where('name', 'JCB 3CX Backhoe Loader')->value('id');
        $machine2 = DB::table('machines')->where('name', 'Manitou MT 1840')->value('id');
        $machine3 = DB::table('machines')->where('name', 'Caterpillar 320 GX')->value('id');

        $reservations = [
            [
                'machine_id'  => $machine1,
                'client_id'   => $clientId,
                'start_date'  => '2026-05-10',
                'end_date'    => '2026-05-15',
                'status'      => 'accepted',
                'total_price' => 2500 * 5,   // 5 jours
                'motif'       => null,
            ],
            [
                'machine_id'  => $machine2,
                'client_id'   => $clientId,
                'start_date'  => '2026-05-20',
                'end_date'    => '2026-05-22',
                'status'      => 'pending',
                'total_price' => 1800 * 2,   // 2 jours
                'motif'       => null,
            ],
            [
                'machine_id'  => $machine3,
                'client_id'   => $clientId,
                'start_date'  => '2026-04-01',
                'end_date'    => '2026-04-07',
                'status'      => 'completed',
                'total_price' => 3200 * 6,   // 6 jours
                'motif'       => null,
            ],
        ];

        foreach ($reservations as $res) {
            if (!$res['machine_id'] || !$clientId) continue;

            $exists = DB::table('reservations')
                ->where('machine_id', $res['machine_id'])
                ->where('client_id',  $clientId)
                ->where('start_date', $res['start_date'])
                ->exists();

            if (!$exists) {
                DB::table('reservations')->insert(array_merge($res, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // ══════════════════════════════════════
        //  RÉSUMÉ
        // ══════════════════════════════════════
        $this->command->info('');
        $this->command->info('✅ Demo data seeded avec succès !');
        $this->command->table(
            ['Rôle', 'Email', 'Password'],
            [
                ['👑 Admin',  'admin@rentify.ma', 'password'],
                ['🔑 Owner',  'karim@rentify.ma', 'password'],
                ['👤 Client', 'fadwa@rentify.ma', 'password'],
            ]
        );
        $this->command->info('🏗  ' . DB::table('machines')->count() . ' machines en base');
        $this->command->info('📋 ' . DB::table('reservations')->count() . ' réservations en base');
    }
}