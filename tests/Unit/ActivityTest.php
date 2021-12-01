<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Facades\Tests\SetUp\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ActivityTest extends TestCase
{
    use RefreshDatabase;


    public function testItHasAUser()
    {
        $user = $this->signIn();

        $project = ProjectFactory::ownedBy($user)->create();
        $this->assertEquals($user->id, $project->activity->first()->user->id);

    }


}
