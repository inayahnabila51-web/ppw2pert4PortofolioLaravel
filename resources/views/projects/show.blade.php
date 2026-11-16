@extends('layouts.app')

@section('title', 'Detail Project')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h1>{{ $project->title }}</h1>
    <p class="text-muted">
        Kategori: {{ $project->category->name ?? '-' }} |
        Status: {{ ucfirst($project->status) }} |
        Slug: {{ $project->slug ?? '-' }}
    </p>

    @if ($project->thumbnail)
        <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}" class="img-fluid mb-3" style="max-height:300px">
    @endif

    @if ($project->short_description)
        <p class="lead">{{ $project->short_description }}</p>
    @endif
    <p>{{ $project->description }}</p>

    <div class="mb-3">
        @foreach ($project->technologies as $tech)
            <span class="badge bg-secondary">{{ $tech->name }}</span>
        @endforeach
    </div>

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

    <a href="{{ route('admin.projects.edit', $project->id) }}">Edit</a> |

    <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" style="display:inline"
        onsubmit="return confirm('Yakin mau hapus project ini?');">
        @csrf
        @method('DELETE')
        <button type="submit" style="color:red; background:none; border:none; cursor:pointer;">Delete</button>
    </form>

    | <a href="{{ route('admin.projects.index') }}">Back to List</a>
</div>
@endsection