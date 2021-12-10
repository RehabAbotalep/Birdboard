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

    public function store()
    {
        $project = auth()->user()->projects()
            ->create($this->validateRequest());

        if(request()->has('tasks')){
            $project->addTasks(request('tasks'));
        }

        if (request()->wantsJson()) {
            return ['message' => $project->path()];
        }

        return redirect($project->path());
    }

    public function show(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Project $project)
    {
        $this->authorize('update', $project);

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
     * Validate the request attributes.
     *
     * @return array
     */
    protected function validateRequest(): array
    {
        return request()->validate([
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'notes' => 'nullable'
        ]);
    }
}
