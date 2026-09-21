@extends('layouts.app')

@section('title', 'Detail Project')

@section('content')
<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1>{{ $project->title }}</h1>
        <p>{{ $project->description }}</p>
    </div>
</div>
@endsection