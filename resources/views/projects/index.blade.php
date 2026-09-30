@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h1>Projects</h1>

    <p>Total Project: {{ $totalProjects }}</p>
    @if($latestProject)
        <p>Project Terbaru: {{ $latestProject->title }}</p>
    @endif

    @if(count($projects) > 0)
        @foreach ($projects as $project)
            <div class="well">
                <h3><a href="/projects/{{ $project->id }}">{{ $project->title }}</a></h3>
            </div>
        @endforeach
    @else
        <h3>Belum ada data project.</h3>
    @endif
    
    <a href="{{ route('projects.trash') }}">🗑 Lihat Trash</a>
    <a href="{{ route('projects.create') }}">+ Tambah Project</a>
</div>
@endsection