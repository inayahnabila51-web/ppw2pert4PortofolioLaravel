@extends('layouts.app')

@section('title', 'Tambah Project')

@section('content')
<div class="container">
    <h1>Add Project</h1>

    @foreach ($errors->all() as $error)
        <div class="alert alert-danger">{{ $error }}</div>
    @endforeach

    <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
        </div>
        <div class="mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="status">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="category_id">Kategori</label>
            <select class="form-select" id="category_id" name="category_id">
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="thumbnail">Thumbnail (min 400x300, maks 2 MB)</label>
            <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection