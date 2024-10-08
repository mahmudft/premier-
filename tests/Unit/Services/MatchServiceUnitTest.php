<?php

use PHPUnit\Framework\TestCase;
use App\Implementations\{MatchRepository};
use App\Services\{MatchService, TeamService};

class MatchServiceUnitTest extends TestCase
{
    protected $matchService;
    protected $matchRepository;
    protected $teamService;

    protected function setUp(): void
    {
        $this->matchRepository = $this->createMock(MatchRepository::class);
        $this->teamService = $this->createMock(TeamService::class);
        $this->matchService = new MatchService($this->matchRepository, $this->teamService);
    }

    public function testCalculateScore()
    {
        $match_couple = [
            ['strength' => 16], // Home team
            ['strength' => 9]   // Away team
        ];
        $result = $this->matchService->calculateScore($match_couple);
        $this->assertEquals(['home' => 3, 'away' => 1], $result);
    }

    public function testCreateRandomMatch()
    {
        $match_couple = [
            ['id' => 1, 'strength' => 16], // Home team
            ['id' => 2, 'strength' => 9]   // Away team
        ];
        $this->matchRepository->expects($this->once())
            ->method('create')
            ->with([
                'home_team_id' => 1,
                'away_team_id' => 2,
                'home_score' => 3, 
                'away_score' => 1,
                'week' => 1
            ]);
        $this->matchService->createRandomMatch($match_couple, 1);
    }
}
