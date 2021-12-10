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

    public function testNonOwnersMayNotInviteUsers()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::create();

        $assertInvitationForbidden = function () use($user, $project){
            $this->actingAs($user)
                ->post($project->path().'/invitations')
                ->assertForbidden();
        };

        $assertInvitationForbidden();

        $project->invite($user);

        $assertInvitationForbidden();
    }

    public function testAProjectOwnerCanInviteUsers()
    {
        $project = ProjectFactory::create();

        $userToInvite = User::factory()->create();

        $this->actingAs($project->owner)
            ->post($project->path().'/invitations', [
                'email' => $userToInvite->email
            ])
            ->assertRedirect($project->path());
        $this->assertTrue($project->members->contains($userToInvite));
    }

    public function testTheEmailAddressMustBeAssociatedWithAValidBirdBoardAccount()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path(). '/invitations',[
                'email' => 'notauser@email.com'
            ])
            ->assertSessionHasErrors([
                'email' => 'The user you are inviting must have a Birdboard account.'
            ], null, 'invitations');
    }

    public function testInvitedUsersMayUpdateProjectDetails()
    {
        $project = ProjectFactory::create();
        $project->invite($newUser = User::factory()->create());

        $this->signIn($newUser);

        $this->post(route('project.tasks', $project->id), $attributes = ['body' => 'new Task']);
        $this->assertDatabaseHas('tasks', $attributes);
    }
}
