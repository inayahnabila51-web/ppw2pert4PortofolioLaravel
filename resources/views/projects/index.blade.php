@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="jumbotron jumbotron-fluid">
    <div class="container">
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
        <h1>Projects</h1>

        @if(count($projects) > 0)
            @foreach ($projects as $project)
                <div class="well">
                    <h3><a href="/projects/{{ $project->id }}">{{ $project->title }}</a></h3>
                </div>
            @endforeach
        @else
            <h3>Belum ada data project.</h3>
        @endif

        <a href="{{ route('projects.create') }}">+ Tambah Project</a>
    </div>
</div>
@endsection