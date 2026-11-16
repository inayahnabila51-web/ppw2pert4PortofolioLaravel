<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    private function rules(?int $ignoreId = null): array
    {
        return [
            'title' => ['required', 'min:5', 'max:200',
                        Rule::unique('projects', 'title')->ignore($ignoreId)],
            'short_description' => 'nullable|max:255',
            'description' => 'required|min:10',
            'status' => 'required|in:draft,published',
            'category_id' => 'nullable|exists:categories,id',
            'project_date' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
            'project_url' => 'nullable|url|max:255',
            'repository_url' => 'nullable|url|max:255',
            'technologies' => 'nullable|array',
            'technologies.*' => 'exists:technologies,id',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
                . '|dimensions:min_width=400,min_height=300',
        ];
    }

    // Str::slug + cek bentrok (mis. "Laravel Blog" vs "Laravel-Blog" slug-nya sama)
    private function makeSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;

        while (Project::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = in_array($request->input('status'), ['draft', 'published'])
            ? $request->input('status') : null;

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [5, 10, 25])) {
            $perPage = 10;
        }

        $projects = Project::query()
            ->when($search, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when($status, fn ($q, $s) => $q->where('status', $s))      // Latihan 4
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('projects.index', [
            'projects' => $projects,
            'totalProjects' => Project::count(),
            'latestProject' => Project::latest()->first(),
            'search' => $search,
            'status' => $status,
            'perPage' => $perPage,
        ]);
    }

    public function create()
    {
        return view('projects.create', [
            'categories' => Category::orderBy('name')->get(),
            'technologies' => Technology::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $validated['slug'] = $this->makeSlug($validated['title']);
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('projects/thumbnails', 'public');
        }

        $technologyIds = $validated['technologies'] ?? [];
        unset($validated['technologies']);          // bukan kolom projects

        $project = Project::create($validated);
        $project->technologies()->attach($technologyIds);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project berhasil ditambahkan.');
    }

    public function show($id)
    {
        $project = Project::with(['category', 'images', 'technologies'])->findOrFail($id);
        return view('projects.show', ['project' => $project]);
    }

    public function edit($id)
    {
        return view('projects.edit', [
            'project' => Project::with(['images', 'technologies'])->findOrFail($id),
            'categories' => Category::orderBy('name')->get(),
            'technologies' => Technology::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $validated = $request->validate($this->rules($project->id));

        // slug hanya dibuat ulang kalau title berubah / slug masih kosong
        if ($project->title !== $validated['title'] || !$project->slug) {
            $validated['slug'] = $this->makeSlug($validated['title'], $project->id);
        }
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('thumbnail')) {
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('projects/thumbnails', 'public');
        }

        $technologyIds = $validated['technologies'] ?? [];
        unset($validated['technologies']);

        $project->update($validated);
        $project->technologies()->sync($technologyIds);   // sync = ganti sesuai centang terbaru

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Soft delete: file JANGAN dihapus, supaya project masih utuh saat di-restore
        Project::findOrFail($id)->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project berhasil dihapus.');
    }

    public function trash()
    {
        return view('projects.trash', ['projects' => Project::onlyTrashed()->get()]);
    }

    public function restore($id)
    {
        Project::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('admin.projects.trash')
            ->with('success', 'Project berhasil dikembalikan.');
    }

    public function forceDelete($id)
    {
        $project = Project::withTrashed()->with('images')->findOrFail($id);

        if ($project->thumbnail) {
            Storage::disk('public')->delete($project->thumbnail);
        }
        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $project->forceDelete(); // images & pivot ikut hilang (cascadeOnDelete)

        return redirect()->route('admin.projects.trash')
            ->with('success', 'Project berhasil dihapus permanen.');
    }
}