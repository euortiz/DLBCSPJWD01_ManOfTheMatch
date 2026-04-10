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
                'photo_url'  => 'https://upload.wikimedia.org/wikipedia/commons/b/b4/Lionel-Messi-Argentina-2022-FIFA-World-Cup_%28cropped%29.jpg',
                'vote_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Cristiano Ronaldo',
                'position'   => 'Forward',
                'photo_url'  => 'https://upload.wikimedia.org/wikipedia/commons/8/8c/Cristiano_Ronaldo_2018.jpg',
                'vote_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Kylian Mbappé',
                'position'   => 'Forward',
                'photo_url'  => 'https://upload.wikimedia.org/wikipedia/commons/5/57/2019-07-17_SG_Dynamo_Dresden_vs._Paris_Saint-Germain_by_Sandro_Halank%E2%80%93149_%28cropped%29.jpg',
                'vote_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Erling Haaland',
                'position'   => 'Forward',
                'photo_url'  => 'https://upload.wikimedia.org/wikipedia/commons/3/thirty/Erling_Haaland_2023.png',
                'vote_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Vinicius Jr.',
                'position'   => 'Forward',
                'photo_url'  => 'https://upload.wikimedia.org/wikipedia/commons/5/fifty/Vinicius_Jr_2023.jpg',
                'vote_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
