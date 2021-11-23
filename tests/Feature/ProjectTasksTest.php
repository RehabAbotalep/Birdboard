<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\SetUp\ProjectFactory;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestsCannotAddTaskToAProject()
    {
        $project = ProjectFactory::create();

        $this->post($project->path().'/tasks', ['body' => 'Test Task'])
            ->assertRedirect('login');
    }

    public function testOnlyTheOwnerOfTheProjectMayAddTasks()
    {
        $this->signIn();
        $project = ProjectFactory::create();

        $this->post($project->path().'/tasks', ['body' => 'Test Task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test Task']);

    }

    public function testAProjectCanHaveTasks()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path(). '/tasks', ['body' => 'Test task']);
        $this->get($project->path())->assertSee('Test task');
    }

    public function testOnlyTheOwnerOfTheProjectMayUpdateTask()
    {
        $this->signIn();
        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks[0]->path(), ['body' => 'changed'])
            ->assertForbidden();

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);

    }

    public function testAProjectTaskCanBeUpdate()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => true,
        ]);
        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true,
        ]);
    }

    public function testATaskRequiresABody()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path(). '/tasks', ['body' => ''])
            ->assertSessionHasErrors('body');
    }
}
