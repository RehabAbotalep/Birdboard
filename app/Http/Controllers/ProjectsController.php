<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects;
        return view('projects.index', compact('projects'));
    }

    public function store(): RedirectResponse
    {
        $attributes = request()->validate([
            "title" => 'required',
            'description' => 'required|max:100',
            'notes' => 'min:3'
        ]);

        $project = auth()->user()->projects()->create($attributes);

        return redirect($project->path());
    }

    public function show(Project $project)
    {
        $this->authorize('manage', $project);

        return view('projects.show', compact('project'));
    }

    public function update(Project $project)
    {
        $this->authorize('manage', $project);

        $project->update(request(['notes']));
        return redirect($project->path());


    }
}
