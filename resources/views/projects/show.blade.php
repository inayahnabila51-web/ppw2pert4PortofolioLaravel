@extends('layouts.app')

@section('title', 'Detail Project')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h1>{{ $project->title }}</h1>
    <p>{{ $project->description }}</p>

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