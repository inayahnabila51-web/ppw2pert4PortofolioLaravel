<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        return view('skills.index', ['skills' => Skill::orderByDesc('level')->get()]);
    }

    public function edit(Skill $skill)
    {
        return view('skills.edit', compact('skill'));
    }

    public function update(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'name'  => 'required|max:100',
            'level' => 'required|integer|min:0|max:100',
        ]);

        $skill->update($validated);

        return redirect()->route('skills.index')->with('success', 'Skill diperbarui.');
    }
}