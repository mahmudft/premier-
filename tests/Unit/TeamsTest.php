<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Team;

class TeamsTest extends TestCase
{
    /**
     * [Test Case for Teams] - Check if teams are inserted to database and Length will be 4
     */

    public function test_teams()
    {

        $teams = Team::all()->count();
        return $this->assertEquals($teams, 4);
        
    }
}
