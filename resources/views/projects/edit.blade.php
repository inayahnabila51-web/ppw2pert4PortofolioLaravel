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

    <form action="{{ route('projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $project->title) }}">
        </div>
        <div class="mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $project->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="status">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="draft" {{ old('status', $project->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status', $project->status) == 'published' ? 'selected' : '' }}>Published</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="category_id">Kategori</label>
            <select class="form-select" id="category_id" name="category_id">
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $project->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
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
    </form>

    <hr class="my-4">

    <h3>Gallery ({{ $project->images->count() }}/10)</h3>

    <form action="{{ route('projects.images.store', $project->id) }}" method="POST" enctype="multipart/form-data" class="mb-4">
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
                        <form action="{{ route('projects.images.update', [$project->id, $img->id]) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <input type="text" name="caption" class="form-control form-control-sm mb-1"
                                   placeholder="Caption" value="{{ $img->caption }}">
                            <button class="btn btn-sm btn-primary">Simpan caption</button>
                        </form>
                        <form action="{{ route('projects.images.destroy', [$project->id, $img->id]) }}" method="POST"
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