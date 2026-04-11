<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to allow re-seeding safely
        DB::table('votes')->delete();
        DB::table('players')->delete();

        DB::table('players')->insert([
            [
                'name'       => 'Lionel Messi',
                'position'   => 'Forward',
                'photo_url'  => '/players/messi.webp',
                'vote_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Cristiano Ronaldo',
                'position'   => 'Forward',
                'photo_url'  => '/players/cr7.webp',
                'vote_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Kylian Mbappé',
                'position'   => 'Forward',
                'photo_url'  => '/players/mbappe.png',
                'vote_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Erling Haaland',
                'position'   => 'Forward',
                'photo_url'  => '/players/haaland.webp',
                'vote_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Vinicius Jr.',
                'position'   => 'Forward',
                'photo_url'  => '/players/vini.jpg',
                'vote_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Lamine Yamal',
                'position'   => 'Forward',
                'photo_url'  => '/players/yamal.webp',
                'vote_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
