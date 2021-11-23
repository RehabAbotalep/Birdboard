<?php


namespace Tests\SetUp;


use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class ProjectFactory
{
    private $tasksCount;
    private $user;

    public function withTasks($count): ProjectFactory
    {
        $this->tasksCount = $count;
        return $this;
    }

    public function ownedBy($user)
    {
        $this->user = $user;
        return $this;
    }

    public function create()
    {
        $project = Project::factory()->create([
            'owner_id' => $this->user ?? User::factory()->create()->id
        ]);

        Task::factory($this->tasksCount)->create([
            'project_id' => $project->id
        ]);

        return $project;

    }
}
