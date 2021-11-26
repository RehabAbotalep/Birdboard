<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\SetUp\ProjectFactory;
use Tests\TestCase;

class ActivityFeedTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatingAProjectRecordeActivity()
    {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);
        $this->assertEquals('created', $project->activity[0]->description);
    }

    public function testUpdatingAProjectRecordActivity()
    {
        $project = ProjectFactory::create();

        $project->update(['title' => 'changed']);

        $this->assertCount(2, $project->activity);
        $this->assertEquals('updated', $project->activity->last()->description);


    }

    public function testGeneratingANewTaskRecordAProjectActivity()
    {
        $project = ProjectFactory::create();

        $project->addTask('new task');

        $this->assertCount(2, $project->activity);
        $this->assertEquals('created-task', $project->activity->last()->description);

    }

    public function testCompletingANewTaskRecordAProjectActivity()
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
}
