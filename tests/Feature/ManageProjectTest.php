<?php

namespace Tests\Feature;

use App\Models\Project;
use Facades\Tests\SetUp\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class ManageProjectTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    public function testOnlyAuthenticatedUserCanAddAProject()
    {
        $attributes = Project::factory()->raw();
        $this->post('projects', $attributes)->assertRedirect('/login');

    }

    public function testGuestCannotViewASingleProject()
    {
        $project = Project::factory()->create();
        $this->get('projects/'.$project->id)->assertRedirect('/login');
    }

    public function testGuestCannotViewAllProjects()
    {
        $this->get('projects')->assertRedirect('/login');
    }

    public function testAUserCanCreateAProject()
    {
        $this->signIn();
        $this->get('projects/create')->assertOk();

        $this->followingRedirects()
            ->post('projects', $attributes = Project::factory()->raw())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    public function testTasksCanBeIncludedAsPartANewProjectCreation()
    {
        $this->signIn();

        $attributes = Project::factory()->raw();
        $attributes['tasks'] = [
            ['body' => 'task1'],
            ['body' => 'task2'],
        ];
        $this->post('projects', $attributes);
        $this->assertCount(2, Project::first()->tasks);
    }

    public function testAUserCanSeeAllProjectTheyHaveBeenInvitedTo()
    {
        $project = tap(Project::factory()->create())->invite($this->signIn());

        $this->get('/projects')->assertSee($project->title);
    }

    public function testAnAuthorizedCannotDeleteTheProject()
    {
        $project = ProjectFactory::create();
        $this->delete($project->path())->assertRedirect('login');

        $user = $this->signIn();
        $this->actingAs($user)->delete($project->path())->assertForbidden();

        $project->invite($user);
        $this->actingAs($user)->delete($project->path())->assertForbidden();

    }

    public function testAUserCanDeleteAProject()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->delete($project->path())
            ->assertRedirect('/projects');
        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    public function testAUserCanUpdateAProject()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = [
                'title' => 'changed',
                'notes' => 'updated',
                'description' => 'changed'
            ])
            ->assertRedirect($project->path());
        $this->get($project->path(). '/edit')->assertOk();

        $this->assertDatabaseHas('projects', $attributes);

    }

    public function testAUserCanUpdateProjectGeneralNotes()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = ['notes' => 'updated'])
            ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);
    }

    public function testOnlyTheOwnerOfTheProjectMayUpdateIt()
    {
        $this->signIn();
        $project = Project::factory()->create();

        $this->patch($project->path(), ['notes' => 'updated'])
            ->assertForbidden();
    }

    public function testCannotAddProjectWithEmptyTitle()
    {
        $this->signIn();
        $attributes = Project::factory()->raw(["title" => '']);
        $this->post('projects', $attributes)->assertSessionHasErrors('title');
    }

    public function testCannotAddProjectWithEmptyDescription()
    {
        $this->signIn();
        $attributes = Project::factory()->raw(["description" => '']);
        $this->post('projects', $attributes)->assertSessionHasErrors('description');
    }

    public function testUsersCanViewTheirProjects()
    {
        $user = $this->signIn();
        $project = Project::factory()->create(["owner_id" => $user->id]);

        $this->get('projects/'.$project->id)->assertOk();
    }

    public function testAUserCannotSeeOthersProjects()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $this->get($project->path())->assertStatus(403);
    }
    protected function setUp(): void
    {
        parent::setUp();
    }


}
