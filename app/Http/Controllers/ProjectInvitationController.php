<?php

namespace App\Http\Controllers;


use App\Http\Requests\ProjectInvitationRequest;
use App\Models\Project;
use App\Models\User;


class ProjectInvitationController extends Controller
{
    public function store(Project $project, ProjectInvitationRequest $request)
    {
        $user = User::whereEmail($request->email)->first();

        $project->invite($user);

        return redirect($project->path());
    }
}
