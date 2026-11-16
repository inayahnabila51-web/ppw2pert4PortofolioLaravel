<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectGalleryController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'images' => 'required|array|max:10',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096|dimensions:min_width=800', // Latihan
        ]);

        // Latihan: maksimal 10 gambar per project
        $sisa = 10 - $project->images()->count();
        if (count($request->file('images')) > $sisa) {
            return back()->withErrors(['images' => "Maksimal 10 gambar per project. Sisa slot: $sisa."]);
        }

        $order = ProjectImage::where('project_id', $project->id)->max('sort_order') ?? -1;

        foreach ($request->file('images') as $file) {
            $project->images()->create([
                'image' => $file->store('projects/gallery', 'public'),
                'sort_order' => ++$order,
            ]);
        }

        return back()->with('success', 'Gambar berhasil diupload.');
    }

    // Latihan: edit caption
    public function update(Request $request, Project $project, ProjectImage $image)
    {
        abort_if($image->project_id !== $project->id, 404);

        $image->update($request->validate(['caption' => 'nullable|max:150']));

        return back()->with('success', 'Caption diperbarui.');
    }

    public function destroy(Project $project, ProjectImage $image)
    {
        abort_if($image->project_id !== $project->id, 404);

        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'Gambar dihapus.');
    }
}