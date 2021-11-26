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

    public function testItCanBeCompleted()
    {
        $task = Task::factory()->create();
        $this->assertFalse($task->completed);

        $task->complete();
        $this->assertTrue($task->fresh()->completed);

    }

    public function testItCanBeMarkedAsInCompleted()
    {
        $task = Task::factory()->create(['completed' => true]);
        $this->assertTrue($task->completed);

        $task->incomplete();
        $this->assertFalse($task->fresh()->completed);

    }
}
