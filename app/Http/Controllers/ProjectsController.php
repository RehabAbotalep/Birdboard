<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->accessibleProjects();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(): RedirectResponse
    {
        $project = auth()->user()->projects()->create($this->validateRequest());

        return redirect($project->path());
    }

    public function show(Project $project)
    {
        $this->authorize('manage', $project);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Project $project)
    {
        $this->authorize('manage', $project);

        $project->update($this->validateRequest());
        return redirect($project->path());

    }

    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);

        $project->delete();
        return redirect('/projects');
    }

    /**
     * @return array
     */
    protected function validateRequest(): array
    {
        return request()->validate([
            "title" => 'sometimes|required',
            'description' => 'sometimes|required|max:100',
            'notes' => 'nullable'
        ]);
    }
}
