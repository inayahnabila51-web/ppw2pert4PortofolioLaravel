@extends('layouts.app')

@section('title', 'Tambah Project')

@section('content')
<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1>Add Project</h1>
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5"></textarea>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="draft" {{ (old('status', '') == 'draft') ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ (old('status', '') == 'published') ? 'selected' : '' }}>Published</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
@endsection