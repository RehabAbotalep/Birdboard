<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\SetUp\ProjectFactory;
use Tests\TestCase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatingAProject()
    {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);
        $this->assertEquals('created', $project->activity[0]->description);
    }

    public function testUpdatingAProject()
    {
        $project = ProjectFactory::create();

        $project->update(['title' => 'changed']);

        $this->assertCount(2, $project->activity);
        $this->assertEquals('updated', $project->activity->last()->description);


    }

    public function testGeneratingANewTask()
    {
        $project = ProjectFactory::create();

        $project->addTask('new task');

        $this->assertCount(2, $project->activity);
        $this->assertEquals('created-task', $project->activity->last()->description);

    }

    public function testCompletingATask()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'changed',
                'completed' => true,
            ]);

        $this->assertCount(3, $project->activity);
        $this->assertEquals('completed-task', $project->activity->last()->description);

    }

    public function testInCompletingATask()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'changed',
                'completed' => true,
            ]);

        $this->assertCount(3, $project->activity);
        $this->assertEquals('completed-task', $project->activity->last()->description);

        $this->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => false,
        ]);
        $project->refresh();

        $this->assertCount(4, $project->activity);
        $this->assertEquals('incompleted-task', $project->activity->last()->description);

    }

    public function testDeletingATask()
    {
        $project = ProjectFactory::withTasks(1)->create();
        $project->tasks[0]->delete();

        $this->assertCount(3, $project->activity);
    }
}
