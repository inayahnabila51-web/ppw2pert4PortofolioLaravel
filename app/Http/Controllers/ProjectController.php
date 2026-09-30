<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $data = [
            'projects' => Project::all(),
            'totalProjects' => Project::count(),
            'latestProject' => Project::latest()->first(),
        ];
        return view('projects.index')->with($data);
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:5|max:200',
            'description' => 'required|min:10',
            'status' => 'required|in:draft,published',
        ]);

        Project::create($request->only(['title', 'description', 'status']));

        return redirect()->route('projects.index')
            ->with('success', 'Project berhasil ditambahkan.');
    }

    public function show($id)
    {
        $data = [
            'project' => Project::findOrFail($id)
        ];
        return view('projects.show')->with($data);
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return view('projects.edit', ['project' => $project]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|min:5|max:200',
            'description' => 'required|min:10',
            'status' => 'required|in:draft,published',
        ]);

        $project = Project::findOrFail($id);
        $project->update($validatedData);

        return redirect()->route('projects.index')
            ->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project berhasil dihapus.');
    }

    public function trash()
    {
        $data = [
            'projects' => Project::onlyTrashed()->get()
        ];
        return view('projects.trash')->with($data);
    }

    public function restore($id)
    {
        Project::withTrashed()->find($id)->restore();

        return redirect()->route('projects.trash')
            ->with('success', 'Project berhasil dikembalikan.');
    }

    public function forceDelete($id)
    {
        Project::withTrashed()->find($id)->forceDelete();

        return redirect()->route('projects.trash')
            ->with('success', 'Project berhasil dihapus permanen.');
    }
}