<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    public function run(): void
    {
        Project::truncate(); // kosongkan tabel dulu supaya tidak dobel

        for ($i = 1; $i <= 10; $i++) {
            Project::create([
                'title' => 'Project Judul Ke-' . $i,
                'description' => 'Deskripsi untuk project judul ke-' . $i,
                'status' => $i % 2 == 0 ? 'published' : 'draft',
            ]);
        }
    }
}