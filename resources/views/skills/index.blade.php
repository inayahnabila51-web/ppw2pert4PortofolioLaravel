@extends('layouts.app')
@section('title', 'Skills')
@section('content')
<div class="container">
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <h1>Skills</h1>
    <table class="table">
        <tr><th>Nama</th><th>Level</th><th></th></tr>
        @foreach ($skills as $skill)
            <tr>
                <td>{{ $skill->name }}</td>
                <td>{{ $skill->level }}</td>
                <td><a href="{{ route('skills.edit', $skill) }}">Edit</a></td>
            </tr>
        @endforeach
    </table>
</div>
@endsection