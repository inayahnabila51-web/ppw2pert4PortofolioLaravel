<?php

namespace App\Http\Controllers;

use App\Models\{Category, Project, Skill};

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalProjects'   => Project::count(),
            'publishedCount'  => Project::published()->count(),
            'draftCount'      => Project::where('status', 'draft')->count(),
            'totalCategories' => Category::count(),
            'totalSkills'     => Skill::count(),
            'topSkills'       => Skill::orderByDesc('level')->take(3)->get(),
            'categories'      => Category::withCount('projects')->get(),
            'latestProjects'  => Project::with('category')->latest()->take(5)->get(),
        ]);
    }
}