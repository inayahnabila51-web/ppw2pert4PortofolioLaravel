@extends('layouts.app')

@section('title', 'Trash Projects')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h1>Trash - Project Terhapus</h1>

    @if(count($projects) > 0)
        @foreach ($projects as $project)
            <div class="well">
                <h3>{{ $project->title }}</h3>
                <p>{{ $project->description }}</p>

                <form action="{{ route('projects.restore', $project->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">Restore</button>
                </form>

                <form action="{{ route('projects.forceDelete', $project->id) }}" method="POST" style="display:inline"
                    onsubmit="return confirm('Data akan dihapus PERMANEN dan tidak bisa dikembalikan. Yakin?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                </form>
            </div>
        @endforeach
    @else
        <h3>Tidak ada data di trash.</h3>
    @endif

    <a href="{{ route('projects.index') }}">Kembali ke Daftar Project</a>
</div>
@endsection