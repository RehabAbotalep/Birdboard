<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function testItHasAPath()
    {
        $project = Project::factory()->create();
        $this->assertEquals('/projects/' . $project->id, $project->path());
    }

    public function testItBelongsToAnOwner()
    {
        $project = Project::factory()->create();
        $this->assertInstanceOf(User::class, $project->owner);
    }

    public function testItCanAddATask()
    {
        $project = Project::factory()->create();
        $project->addTask('Test task');
        $this->assertCount(1, $project->tasks);


    }
}
