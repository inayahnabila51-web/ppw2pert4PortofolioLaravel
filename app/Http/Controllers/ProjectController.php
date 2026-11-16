<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Project;

class ProjectController extends Controller
{
    private function rules(): array
    {
        return [
            'title' => 'required|min:5|max:200',
            'description' => 'required|min:10',
            'status' => 'required|in:draft,published',
            'category_id' => 'nullable|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
                . '|dimensions:min_width=400,min_height=300',
        ];
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [5, 10, 25])) {
            $perPage = 10;
        }

        $projects = Project::query()
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $data = [
            'projects' => $projects,
            'totalProjects' => Project::count(),
            'latestProject' => Project::latest()->first(),
            'search' => $search,
            'perPage' => $perPage,
        ];

        return view('projects.index')->with($data);
    }

    public function create()
    {
        return view('projects.create', ['categories' => Category::all()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());


        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('projects/thumbnails', 'public');
        }


        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project berhasil ditambahkan.');
    }

    public function show($id)
    {
        $project = Project::with(['category', 'images'])->findOrFail($id);
        return view('projects.show', ['project' => $project]);
    }

    public function edit($id)
    {
        return view('projects.edit', [
            'project' => Project::with('images')->findOrFail($id),
            'categories' => Category::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->rules());
        $project = Project::findOrFail($id);

        if ($request->hasFile('thumbnail')) {
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('projects/thumbnails', 'public');
        }

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Soft delete: file JANGAN dihapus, supaya project masih utuh saat di-restore
        Project::findOrFail($id)->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project berhasil dihapus.');
    }

    public function trash()
    {
        return view('projects.trash', ['projects' => Project::onlyTrashed()->get()]);
    }

    public function restore($id)
    {
        Project::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('projects.trash')
            ->with('success', 'Project berhasil dikembalikan.');
    }

    public function forceDelete($id)
    {
        $project = Project::withTrashed()->with('images')->findOrFail($id);

        // Hapus permanen: baru file fisiknya ikut dihapus
        if ($project->thumbnail) {
            Storage::disk('public')->delete($project->thumbnail);
        }
        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $project->forceDelete(); // baris project_images ikut hilang (cascadeOnDelete)

        return redirect()->route('projects.trash')
            ->with('success', 'Project berhasil dihapus permanen.');
    }
}