@extends('layouts.app')

@section('title', 'Detail Project')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h1>{{ $project->title }}</h1>
    <p class="text-muted">Kategori: {{ $project->category->name ?? '-' }}</p>

    @if ($project->thumbnail)
        <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}" class="img-fluid mb-3" style="max-height:300px">
    @endif

    <p>{{ $project->description }}</p>

    @if ($project->images->count())
        <h4>Gallery</h4>
        <div class="row g-3 mb-3">
            @foreach ($project->images as $img)
                <div class="col-md-4">
                    <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $img->caption ?: $project->title }}" class="img-fluid">
                    @if ($img->caption)
                        <small class="text-muted d-block">{{ $img->caption }}</small>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <a href="{{ route('projects.edit', $project->id) }}">Edit</a> |

    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline"
        onsubmit="return confirm('Yakin mau hapus project ini?');">
        @csrf
        @method('DELETE')
        <button type="submit" style="color:red; background:none; border:none; cursor:pointer;">Delete</button>
    </form>

    | <a href="{{ route('projects.index') }}">Back to List</a>
</div>
@endsection