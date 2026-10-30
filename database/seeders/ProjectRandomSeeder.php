<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectRandomSeeder extends Seeder
{
    public function run(): void
    {
        // JANGAN pakai truncate() di sini, nanti 10 data dari seeder pertama ikut terhapus

        for ($i = 1; $i <= 25; $i++) {
            Project::create([
                'title' => ucwords(fake()->words(3, true)),
                'description' => fake()->sentence(12),
                'status' => fake()->randomElement(['draft', 'published']),
            ]);
        }
    }
}