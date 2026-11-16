@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
<div class="container">
    <h1>Edit Project</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger">{{ $error }}</div>
    @endforeach

    <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $project->title) }}">
            @if ($project->slug)
                <small class="text-muted">Slug saat ini: {{ $project->slug }}</small>
            @endif
        </div>

        <div class="mb-3">
            <label for="short_description">Deskripsi singkat</label>
            <input type="text" class="form-control" id="short_description" name="short_description"
                   value="{{ old('short_description', $project->short_description) }}">
        </div>

        <div class="mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $project->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="status">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="draft" @selected(old('status', $project->status) == 'draft')>Draft</option>
                <option value="published" @selected(old('status', $project->status) == 'published')>Published</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="category_id">Kategori</label>
            <select class="form-select" id="category_id" name="category_id">
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $project->category_id) == $category->id)>
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
                           @checked(in_array($tech->id, old('technologies', $project->technologies->pluck('id')->all())))>
                    <label class="form-check-label" for="tech{{ $tech->id }}">{{ $tech->name }}</label>
                </div>
            @endforeach
        </div>

        <div class="mb-3">
            <label for="project_date">Tanggal project</label>
            <input type="date" class="form-control" id="project_date" name="project_date"
                   value="{{ old('project_date', $project->project_date?->format('Y-m-d')) }}">
        </div>

        <div class="mb-3">
            <label for="project_url">URL project</label>
            <input type="url" class="form-control" id="project_url" name="project_url"
                   value="{{ old('project_url', $project->project_url) }}">
        </div>

        <div class="mb-3">
            <label for="repository_url">URL repository</label>
            <input type="url" class="form-control" id="repository_url" name="repository_url"
                   value="{{ old('repository_url', $project->repository_url) }}">
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="is_featured" value="0">
            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1"
                   @checked(old('is_featured', $project->is_featured))>
            <label class="form-check-label" for="is_featured">Project unggulan</label>
        </div>

        <div class="mb-3">
            <label for="thumbnail">Thumbnail baru (kosongkan jika tidak diganti)</label>
            @if ($project->thumbnail)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}" style="height:100px">
                </div>
            @endif
            <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Kembali</a>
    </form>

    <hr class="my-4">

    <h3>Gallery ({{ $project->images->count() }}/10)</h3>

    <form action="{{ route('admin.projects.images.store', $project->id) }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <input type="file" name="images[]" class="form-control mb-2" accept="image/*" multiple>
        <button class="btn btn-success">Upload Gambar</button>
    </form>

    <div class="row g-3">
        @foreach ($project->images as $img)
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('storage/' . $img->image) }}" class="card-img-top" alt="{{ $img->caption ?: $project->title }}">
                    <div class="card-body">
                        <form action="{{ route('admin.projects.images.update', [$project->id, $img->id]) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <input type="text" name="caption" class="form-control form-control-sm mb-1"
                                   placeholder="Caption" value="{{ $img->caption }}">
                            <button class="btn btn-sm btn-primary">Simpan caption</button>
                        </form>
                        <form action="{{ route('admin.projects.images.destroy', [$project->id, $img->id]) }}" method="POST"
                              onsubmit="return confirm('Hapus gambar ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection