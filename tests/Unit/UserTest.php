<?php

namespace Tests\Unit;

use App\Models\User;
use Facades\Tests\SetUp\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;


class UserTest extends TestCase
{

    public function testAUserHasProjects()
    {
        $user = User::factory()->create();
        $this->assertInstanceOf(Collection::class,  $user->projects);
    }

    public function testAUserHasAnAccessibleProjects()
    {
        $john = $this->signIn();

        ProjectFactory::ownedBy($john)->create();

        $this->assertCount(1, $john->accessibleProjects());

        $sally = User::factory()->create();
        $nick = User::factory()->create();

        $project = tap(ProjectFactory::ownedBy($sally)->create())->invite($nick);

        $this->assertCount(1, $john->accessibleProjects());

        $project->invite($john);

        $this->assertCount(2, $john->accessibleProjects());


    }
}
