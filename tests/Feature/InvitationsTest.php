<?php

namespace Tests\Feature;

use App\Models\User;
use Facades\Tests\SetUp\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    public function testAProjectCanInviteUsers()
    {
        $project = ProjectFactory::create();
        $project->invite($newUser = User::factory()->create());

        $this->signIn($newUser);

        $this->post(route('project.tasks', $project->id), $attributes = ['body' => 'new Task']);
        $this->assertDatabaseHas('tasks', $attributes);
    }
}
