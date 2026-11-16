@extends('layouts.app')

@section('title', 'Projects')

@section('content')
    <h1 class="mb-3">Project Saya</h1>

    <form method="GET" action="{{ route('projects') }}" class="row g-2 mb-3">
        <div class="col-md-3">
            <input name="search" class="form-control" placeholder="Cari project..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="category" class="form-select">
                <option value="">Semua kategori</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->slug }}" @selected(request('category') === $c->slug)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="technology" class="form-select">
                <option value="">Semua technology</option>
                @foreach ($technologies as $t)
                    <option value="{{ $t->slug }}" @selected(request('technology') === $t->slug)>{{ $t->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="sort" class="form-select">
                <option value="">Terbaru</option>
                <option value="terlama" @selected(request('sort') === 'terlama')>Terlama</option>
                <option value="az" @selected(request('sort') === 'az')>Judul A-Z</option>
                <option value="tanggal" @selected(request('sort') === 'tanggal')>Tanggal project</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary">Cari</button>
            @if (request()->hasAny(['search', 'category', 'technology', 'sort']))
                <a href="{{ route('projects') }}" class="btn btn-secondary">Reset</a>
            @endif
        </div>
    </form>

    <p class="text-muted">{{ $projects->total() }} hasil ditemukan</p>

    <div class="row g-3">
        @forelse ($projects as $project)
            <div class="col-md-4">
                <div class="card h-100">
                    @if ($project->thumbnail)
                        <img src="{{ asset('storage/' . $project->thumbnail) }}" class="card-img-top" alt="{{ $project->title }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('projects.show', $project) }}">{{ $project->title }}</a>
                        </h5>
                        <p class="card-text">{{ $project->short_description }}</p>
                        <span class="badge bg-primary">{{ $project->category->name ?? 'Tanpa kategori' }}</span>
                        @foreach ($project->technologies as $tech)
                            <span class="badge bg-secondary">{{ $tech->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><p>Tidak ada project yang cocok.</p></div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $projects->links() }}
    </div>
@endsection