@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard</h1>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3"><x-stat-card label="Projects" :value="$totalProjects" /></div>
        <div class="col-6 col-md-3"><x-stat-card label="Published" :value="$publishedCount" color="text-success" /></div>
        <div class="col-6 col-md-3"><x-stat-card label="Draft" :value="$draftCount" color="text-secondary" /></div>
        <div class="col-6 col-md-3"><x-stat-card label="Skills" :value="$totalSkills" color="text-warning" /></div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <h5>Skill Teratas</h5>
            <ul class="list-group">
                @foreach ($topSkills as $skill)
                    <li class="list-group-item d-flex justify-content-between">
                        {{ $skill->name }} <span class="badge bg-warning text-dark">{{ $skill->level }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-4">
            <h5>Project per Kategori</h5>
            <ul class="list-group">
                @foreach ($categories as $category)
                    <li class="list-group-item d-flex justify-content-between">
                        {{ $category->name }} <span class="badge bg-primary">{{ $category->projects_count }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-4">
            <h5>5 Project Terbaru</h5>
            <ul class="list-group">
                @foreach ($latestProjects as $p)
                    <li class="list-group-item">
                        <a href="{{ route('projects.show', $p->id) }}">{{ $p->title }}</a>
                        <small class="text-muted">({{ $p->category->name ?? 'Tanpa kategori' }})</small>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection