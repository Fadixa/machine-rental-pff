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
        //  USERS — 3 comptes test uniquement
        //  Les nouveaux inscrits n'auront PAS de réservations
        // ══════════════════════════════════════
        $users = [
            [
                'name'     => 'Admin Rentify',
                'email'    => 'admin@rentify.ma',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'phone'    => '+212600000000',
                'city'     => 'Casablanca',
                'bio'      => 'Équipe Rentify.',
            ],
            [
                'name'     => 'Karim Benali',
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
        ];

        // updateOrInsert uniquement pour les 3 comptes test
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
        //  MACHINES — liées à karim@rentify.ma
        // ══════════════════════════════════════
        $ownerId = DB::table('users')->where('email', 'karim@rentify.ma')->value('id');

        $machines = [

            // ── Excavatrice / Pelle ──────────────────────────────────
            [
                'name'           => 'JCB 3CX Backhoe Loader',
                'type'           => 'Excavatrice',
                'description'    => 'Engin polyvalent idéal pour travaux de terrassement.',
                'price_per_day'  => 2800,
                'price_per_hour' => 380,
                'status'         => 'available',
                'city'           => 'Casablanca',
                'location'       => 'Casablanca, Maroc',
                'latitude'       => 33.5731,
                'longitude'      => -7.5898,
                'image'          => 'images/img12.png',
            ],

            // ── Chargeuse télescopique ───────────────────────────────
            [
                'name'           => 'Manitou MT 1840',
                'type'           => 'Chargeuse',
                'description'    => 'Chariot télescopique grande portée.',
                'price_per_day'  => 2400,
                'price_per_hour' => 320,
                'status'         => 'available',
                'city'           => 'Rabat',
                'location'       => 'Rabat, Maroc',
                'latitude'       => 34.0209,
                'longitude'      => -6.8416,
                'image'          => null,
            ],

            // ── Pelle hydraulique grande puissance ──────────────────
            [
                'name'           => 'Caterpillar 320 GX',
                'type'           => 'Excavatrice',
                'description'    => 'Pelle hydraulique haute performance.',
                'price_per_day'  => 4200,
                'price_per_hour' => 560,
                'status'         => 'available',
                'city'           => 'Marrakech',
                'location'       => 'Marrakech, Maroc',
                'latitude'       => 31.6295,
                'longitude'      => -7.9811,
                'image'          => null,
            ],

            // ── Camion benne ─────────────────────────────────────────
            [
                'name'           => 'Camion Benne Volvo FH16',
                'type'           => 'Camion',
                'description'    => 'Camion benne 8x4 pour transport de matériaux.',
                'price_per_day'  => 2200,
                'price_per_hour' => 290,
                'status'         => 'unavailable',
                'city'           => 'Fès',
                'location'       => 'Fès, Maroc',
                'latitude'       => 34.0181,
                'longitude'      => -5.0078,
                'image'          => null,
            ],

            // ── Compacteur standard ──────────────────────────────────
            [
                'name'           => 'Compacteur Bomag BW 213',
                'type'           => 'Compacteur',
                'description'    => 'Rouleau compacteur pour routes et plateformes.',
                'price_per_day'  => 1600,
                'price_per_hour' => 220,
                'status'         => 'available',
                'city'           => 'Tanger',
                'location'       => 'Tanger, Maroc',
                'latitude'       => 35.7595,
                'longitude'      => -5.8340,
                'image'          => 'images/img15.jpeg',
            ],

            // ── Convoi exceptionnel ──────────────────────────────────
            [
                'name'           => 'Scania Convoi Plateau + Excavatrice',
                'type'           => 'Transport',
                'description'    => 'Camion Scania plateau surbaissé pour transport de machines lourdes.',
                'price_per_day'  => 5500,
                'price_per_hour' => 720,
                'status'         => 'available',
                'city'           => 'Casablanca',
                'location'       => 'Casablanca, Maroc',
                'latitude'       => 33.5731,
                'longitude'      => -7.5898,
                'image'          => 'images/img11.png',
            ],

            // ── JCB 3CX Pro ──────────────────────────────────────────
            [
                'name'           => 'JCB 3CX Pro — Édition Chantier',
                'type'           => 'Excavatrice',
                'description'    => 'Chargeuse-pelleteuse JCB 3CX en parfait état, disponible pour terrassement et fouilles.',
                'price_per_day'  => 3200,
                'price_per_hour' => 420,
                'status'         => 'available',
                'city'           => 'Casablanca',
                'location'       => 'Casablanca, Maroc',
                'latitude'       => 33.5820,
                'longitude'      => -7.6120,
                'image'          => 'images/img12.png',
            ],

            // ── JCB 4CX ─────────────────────────────────────────────
            [
                'name'           => 'JCB 4CX Super — Haute Performance',
                'type'           => 'Excavatrice',
                'description'    => 'Pelleteuse-chargeuse JCB 4CX puissance maximale, idéale pour grands chantiers.',
                'price_per_day'  => 4500,
                'price_per_hour' => 600,
                'status'         => 'available',
                'city'           => 'Agadir',
                'location'       => 'Agadir, Maroc',
                'latitude'       => 30.4278,
                'longitude'      => -9.5981,
                'image'          => 'images/img13.png',
            ],

            // ── Niveleuse ────────────────────────────────────────────
            [
                'name'           => 'Terex TG110 Niveleuse',
                'type'           => 'Niveleuse',
                'description'    => 'Niveleuse Terex TG110 pour dressage et finition de plateformes et routes.',
                'price_per_day'  => 3500,
                'price_per_hour' => 460,
                'status'         => 'available',
                'city'           => 'Oujda',
                'location'       => 'Oujda, Maroc',
                'latitude'       => 34.6814,
                'longitude'      => -1.9086,
                'image'          => 'images/img14.png',
            ],

            // ── Compacteur rouleau vibrant ───────────────────────────
            [
                'name'           => 'Compacteur Rouleau Vibrant RV-200',
                'type'           => 'Compacteur',
                'description'    => 'Rouleau vibrant monocylindre pour compactage de sols et remblais.',
                'price_per_day'  => 1800,
                'price_per_hour' => 240,
                'status'         => 'available',
                'city'           => 'Meknès',
                'location'       => 'Meknès, Maroc',
                'latitude'       => 33.8731,
                'longitude'      => -5.5407,
                'image'          => 'images/img15.jpeg',
            ],
        ];

        foreach ($machines as $m) {
            DB::table('machines')->updateOrInsert(
                ['name' => $m['name']],
                array_merge($m, [
                    'owner_id'   => $ownerId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        // ══════════════════════════════════════
        //  RESERVATIONS — liées à fadwa@rentify.ma UNIQUEMENT
        //  Les nouveaux comptes inscrits commencent vides
        // ══════════════════════════════════════
        $fadwaId  = DB::table('users')->where('email', 'fadwa@rentify.ma')->value('id');
        $machine1 = DB::table('machines')->where('name', 'JCB 3CX Backhoe Loader')->value('id');
        $machine2 = DB::table('machines')->where('name', 'Manitou MT 1840')->value('id');
        $machine3 = DB::table('machines')->where('name', 'Caterpillar 320 GX')->value('id');

        // Sécurité : on insère uniquement si fadwa existe et les machines existent
        if (!$fadwaId) return;

        $reservations = [
            [
                'machine_id'  => $machine1,
                'client_id'   => $fadwaId,
                'start_date'  => '2026-05-10',
                'end_date'    => '2026-05-15',
                'status'      => 'accepted',
                'total_price' => 2800 * 5,   // 14 000 MAD
                'motif'       => null,
            ],
            [
                'machine_id'  => $machine2,
                'client_id'   => $fadwaId,
                'start_date'  => '2026-05-20',
                'end_date'    => '2026-05-22',
                'status'      => 'pending',
                'total_price' => 2400 * 2,   // 4 800 MAD
                'motif'       => null,
            ],
            [
                'machine_id'  => $machine3,
                'client_id'   => $fadwaId,
                'start_date'  => '2026-04-01',
                'end_date'    => '2026-04-07',
                'status'      => 'completed',
                'total_price' => 4200 * 6,   // 25 200 MAD
                'motif'       => null,
            ],
        ];

        foreach ($reservations as $res) {
            // Vérification machine existe
            if (!$res['machine_id']) continue;

            // Pas de doublon
            $exists = DB::table('reservations')
                ->where('machine_id', $res['machine_id'])
                ->where('client_id',  $fadwaId)
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
                ['👑 Admin',  'admin@rentify.ma',  'password'],
                ['🔑 Owner',  'karim@rentify.ma',  'password'],
                ['👤 Client', 'fadwa@rentify.ma',  'password'],
            ]
        );
        $this->command->info('🏗  ' . DB::table('machines')->count()    . ' machines en base');
        $this->command->info('📋 ' . DB::table('reservations')->count() . ' réservations en base');
    }
}