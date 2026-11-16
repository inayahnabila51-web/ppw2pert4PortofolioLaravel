@extends('layouts.app')

@section('title', $project->title)

@section('content')
    <a href="{{ route('projects') }}">&larr; Kembali ke Projects</a>

    <h1 class="mt-3">{{ $project->title }}</h1>
    <p class="text-muted">
        {{ $project->category->name ?? 'Tanpa kategori' }}
        @if ($project->project_date) &bull; {{ $project->project_date->format('d M Y') }} @endif
    </p>

    @if ($project->thumbnail)
        <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}" class="img-fluid mb-3" style="max-height:300px">
    @endif

    <p class="lead">{{ $project->short_description }}</p>
    <p>{{ $project->description }}</p>

    <div class="mb-3">
        @foreach ($project->technologies as $tech)
            <span class="badge bg-secondary">{{ $tech->name }}</span>
        @endforeach
    </div>

    <div class="mb-4">
        @if ($project->project_url)
            <a href="{{ $project->project_url }}" target="_blank" class="btn btn-primary">Lihat Project</a>
        @endif
        @if ($project->repository_url)
            <a href="{{ $project->repository_url }}" target="_blank" class="btn btn-outline-dark">Repository</a>
        @endif
    </div>

    @if ($project->images->count())
        <h4>Gallery</h4>
        <div class="row g-3">
            @foreach ($project->images as $img)
                <div class="col-md-4">
                    <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $img->caption ?: $project->title }}" class="img-fluid">
                    @if ($img->caption)<small class="text-muted d-block">{{ $img->caption }}</small>@endif
                </div>
            @endforeach
        </div>
    @endif
@endsection