<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;


class UserTest extends TestCase
{

    public function testItHasAProjects()
    {
        $user = User::factory()->create();
        $this->assertInstanceOf(Collection::class,  $user->projects);
    }
}
