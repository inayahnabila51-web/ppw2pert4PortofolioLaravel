@extends('layouts.app')

@section('title', 'Tambah Project')

@section('content')
<div class="container">
    <h1>Add Project</h1>

    @foreach ($errors->all() as $error)
        <div class="alert alert-danger">{{ $error }}</div>
    @endforeach

    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
        </div>

        <div class="mb-3">
            <label for="short_description">Deskripsi singkat</label>
            <input type="text" class="form-control" id="short_description" name="short_description" value="{{ old('short_description') }}">
        </div>

        <div class="mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="status">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="draft" @selected(old('status') == 'draft')>Draft</option>
                <option value="published" @selected(old('status') == 'published')>Published</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="category_id">Kategori</label>
            <select class="form-select" id="category_id" name="category_id">
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="d-block">Technologies</label>
            @foreach ($technologies as $tech)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="technologies[]"
                           id="tech{{ $tech->id }}" value="{{ $tech->id }}"
                           @checked(in_array($tech->id, old('technologies', [])))>
                    <label class="form-check-label" for="tech{{ $tech->id }}">{{ $tech->name }}</label>
                </div>
            @endforeach
        </div>

        <div class="mb-3">
            <label for="project_date">Tanggal project</label>
            <input type="date" class="form-control" id="project_date" name="project_date" value="{{ old('project_date') }}">
        </div>

        <div class="mb-3">
            <label for="project_url">URL project</label>
            <input type="url" class="form-control" id="project_url" name="project_url" value="{{ old('project_url') }}">
        </div>

        <div class="mb-3">
            <label for="repository_url">URL repository</label>
            <input type="url" class="form-control" id="repository_url" name="repository_url" value="{{ old('repository_url') }}">
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="is_featured" value="0">
            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" @checked(old('is_featured'))>
            <label class="form-check-label" for="is_featured">Project unggulan</label>
        </div>

        <div class="mb-3">
            <label for="thumbnail">Thumbnail (min 400x300, maks 2 MB)</label>
            <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection