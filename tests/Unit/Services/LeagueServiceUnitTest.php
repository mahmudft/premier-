<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\{LeagueService, TeamService};
use App\Implementations\{LeagueTableRepository, TeamRepository};
use App\Models\{Team, LeagueTable};
use App\Helpers\TeamStatistic;
use Illuminate\Database\Eloquent\Collection;
use Mockery;

class LeagueServiceUnitTest extends TestCase
{
    protected $leagueService;
    protected $mockLeagueRepository;
    protected $mockTeamService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockLeagueRepository = Mockery::mock(LeagueTableRepository::class);
        $this->mockTeamService = Mockery::mock(TeamService::class);
        
        $this->leagueService = new LeagueService(
            $this->mockLeagueRepository,
            $this->mockTeamService
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGenerateRandomLeagueRecord()
    {
        $team = new Team();
        $team->id = 1;
        
        $result = $this->leagueService->generateRandomLeagueRecord(1, $team);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('matches_played', $result);
        $this->assertArrayHasKey('team_id', $result);
        $this->assertArrayHasKey('wins', $result);
        $this->assertArrayHasKey('draws', $result);
        $this->assertArrayHasKey('losses', $result);
        $this->assertArrayHasKey('points', $result);
        $this->assertArrayHasKey('goal_difference', $result);
        $this->assertArrayHasKey('weekly', $result);
        
        $this->assertEquals(1, $result['weekly']);
        $this->assertEquals(1, $result['team_id']);
        $this->assertGreaterThanOrEqual(7, $result['matches_played']);
        $this->assertLessThanOrEqual(10, $result['matches_played']);
    }

    public function testCreateInitialStatisticRecord()
    {
        $team = new Team();
        $team->name = 'Test Team';
        $match = new LeagueTable();
        $match->team = $team;
        $result = $this->leagueService->createInitialStatisticRecord($match);
        $this->assertIsArray($result);
        $this->assertEquals('Test Team', $result['name']);
        $this->assertEquals(0, $result['matches_played']);
        $this->assertEquals(0, $result['wins']);
    }
    
}