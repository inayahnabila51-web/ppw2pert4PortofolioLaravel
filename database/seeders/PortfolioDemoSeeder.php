<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PortfolioDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kategori + technology (updateOrCreate: aman dijalankan berulang)
        $categories = collect(['Web Application', 'Mobile Application', 'UI/UX Design', 'Data & AI'])
            ->map(fn ($n) => Category::updateOrCreate(['name' => $n], ['slug' => Str::slug($n)]));

        $techs = collect(['Laravel', 'PHP', 'MySQL', 'Bootstrap', 'Tailwind CSS', 'JavaScript', 'Kotlin', 'Figma'])
            ->map(fn ($n) => Technology::updateOrCreate(['name' => $n], ['slug' => Str::slug($n)]));

        // 2. Isi slug untuk kategori / project lama yang masih NULL
        Category::whereNull('slug')->get()->each(
            fn ($c) => $c->update(['slug' => Str::slug($c->name)])
        );
        Project::withTrashed()->whereNull('slug')->get()->each(
            fn ($p) => $p->update(['slug' => $this->uniqueSlug($p->title, $p->id)])
        );

        // 3. 12 project published (> 9 supaya halaman 2 muncul)
        $titles = [
            'Laravel Blog', 'LovelyTrinkets Store', 'PDE UI UX Redesign', 'Todo List Laravel',
            'Weather App Kotlin', 'Campus Booking System', 'Inventory Management', 'Quiz Mobile App',
            'Company Profile Website', 'Library Information System', 'Expense Tracker', 'Recipe Finder',
        ];

        foreach ($titles as $i => $title) {
            $project = Project::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title'             => $title,
                    'short_description' => "Ringkasan singkat project {$title}.",
                    'description'       => "Deskripsi lengkap project {$title}. Dibuat untuk latihan search, filter, dan sorting.",
                    'status'            => 'published',
                    'category_id'       => $categories->random()->id,
                    'project_date'      => now()->subDays(($i + 1) * 20),
                    'is_featured'       => $i < 3,
                    'project_url'       => 'https://example.com/' . Str::slug($title),
                    'repository_url'    => 'https://github.com/example/' . Str::slug($title),
                ]
            );
            $project->technologies()->sync($techs->random(3)->pluck('id'));
        }
    }

    private function uniqueSlug(string $title, int $ignoreId): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;
        while (Project::withTrashed()->where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}