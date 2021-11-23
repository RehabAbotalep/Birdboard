<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function testItBelongsToAProject()
    {
        $task = Task::factory()->create();
        $this->assertInstanceOf(Project::class, $task->project);
    }

    public function testItHasAPath()
    {
        $task = Task::factory()->create();
        $this->assertEquals('projects/'.$task->project->id.'/tasks/'.$task->id, $task->path());
    }
}
