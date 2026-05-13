<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MachineSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer l'ID du premier utilisateur existant (propriétaire par défaut)
        $userId = DB::table('users')->first()->id;

        // Coordonnées GPS des principales villes marocaines
        $villes = [
            ['city' => 'Casablanca', 'latitude' => 33.5731,  'longitude' => -7.5898],
            ['city' => 'Rabat',      'latitude' => 34.0209,  'longitude' => -6.8416],
            ['city' => 'Marrakech',  'latitude' => 31.6295,  'longitude' => -7.9811],
            ['city' => 'Fès',        'latitude' => 34.0181,  'longitude' => -5.0078],
            ['city' => 'Tanger',     'latitude' => 35.7595,  'longitude' => -5.8340],
            ['city' => 'Agadir',     'latitude' => 30.4278,  'longitude' => -9.5981],
        ];

        // Machines à insérer avec leurs coordonnées GPS
        DB::table('machines')->insert([
            [
                'owner_id'      => $userId,
                'name'          => 'Machine à Café Espresso',
                'type'          => 'Cuisine',
                'description'   => 'Machine professionnelle pour café italien.',
                'price_per_day' => 150.00,
                'price_per_hour'=> 20.00,
                'location'      => 'Casablanca',
                // Coordonnées GPS — Casablanca (légèrement décalées pour éviter les doublons)
                'latitude'      => 33.5731 + (rand(-50, 50) / 1000),
                'longitude'     => -7.5898 + (rand(-50, 50) / 1000),
                'city'          => 'Casablanca',
                'status'        => 'available',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'owner_id'      => $userId,
                'name'          => 'Pétrin de Boulangerie',
                'type'          => 'Industriel',
                'description'   => 'Pétrin industriel pour pâte à pain.',
                'price_per_day' => 500.00,
                'price_per_hour'=> 60.00,
                'location'      => 'Sidi Maarouf',
                // Coordonnées GPS — Casablanca Sidi Maarouf
                'latitude'      => 33.5404 + (rand(-30, 30) / 1000),
                'longitude'     => -7.6824 + (rand(-30, 30) / 1000),
                'city'          => 'Casablanca',
                'status'        => 'available',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}