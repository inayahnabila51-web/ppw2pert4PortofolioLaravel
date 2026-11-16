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

    {{-- Form pencarian + filter status + pilihan jumlah per halaman --}}
    <form action="{{ route('admin.projects.index') }}" method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control"
                   placeholder="Cari judul project..." value="{{ $search }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Semua status</option>
                <option value="draft" @selected($status === 'draft')>Draft</option>
                <option value="published" @selected($status === 'published')>Published</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="per_page" class="form-select" onchange="this.form.submit()">
                @foreach ([5, 10, 25] as $option)
                    <option value="{{ $option }}" @selected($perPage == $option)>
                        {{ $option }} per halaman
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Cari</button>
            @if($search || $status)
                <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </div>
    </form>

    @if(count($projects) > 0)
        @foreach ($projects as $project)
            <div class="well">
                <h3>
                    {{ $projects->firstItem() + $loop->index }}.
                    <a href="{{ route('admin.projects.show', $project->id) }}">{{ $project->title }}</a>
                    <span class="badge {{ $project->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                        {{ ucfirst($project->status) }}
                    </span>
                    @if($project->thumbnail)
                        <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}" style="height:50px">
                    @endif
                </h3>
            </div>
        @endforeach
    @else
        <h3>Belum ada data project.</h3>
    @endif

    {{-- Info halaman --}}
    <div class="mt-4 text-muted small">
        Halaman : {{ $projects->currentPage() }} <br>
        Jumlah Data : {{ $projects->total() }} <br>
        Data Per Halaman : {{ $projects->perPage() }}
    </div>

    {{-- Tombol pagination --}}
    <div class="mt-4">
        {{ $projects->links() }}
    </div>

    <a href="{{ route('admin.projects.trash') }}">🗑 Lihat Trash</a>
    <a href="{{ route('admin.projects.create') }}">+ Tambah Project</a>
</div>
@endsection