<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'a@a',
            'isAdmin' => true,
        ]);

        $this->call([
            QuestionSeeder::class,
            GravadoraSeeder::class,
            GeneroSeeder::class,
            PlanoSeeder::class,
            ArtistaSeeder::class,
            MusicaSeeder::class,
            MusicaArtistaSeeder::class,
            ClienteSeeder::class,
            MusicaClienteSeeder::class,
            QuestionImageSeeder::class,
        ]);
    }
}
