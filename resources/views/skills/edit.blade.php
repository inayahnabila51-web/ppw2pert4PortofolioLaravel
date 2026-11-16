@extends('layouts.app')
@section('title', 'Edit Skill')
@section('content')
<div class="container">
    <h1>Edit Skill</h1>
    @foreach ($errors->all() as $error)<div class="alert alert-danger">{{ $error }}</div>@endforeach
    <form action="{{ route('skills.update', $skill) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $skill->name) }}">
        </div>
        <div class="mb-3">
            <label>Level (0-100)</label>
            <input type="number" name="level" class="form-control" value="{{ old('level', $skill->level) }}">
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('skills.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection