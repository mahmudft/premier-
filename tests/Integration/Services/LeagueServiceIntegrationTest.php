<?php

namespace Tests\Integration\Services;

use Tests\TestCase;
use App\Services\{LeagueService, TeamService};
use App\Implementations\{LeagueTableRepository, TeamRepository};
use Database\Seeders\TeamsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class LeagueServiceIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected LeagueService $leagueService;
    protected LeagueTableRepository $leagueRepository;
    protected TeamService $teamService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assertTrue(
            DB::connection() instanceof \Illuminate\Database\SQLiteConnection,
            'Database connection is not SQLite'
        );
        DB::statement('PRAGMA foreign_keys=ON;');
        $this->leagueRepository = app(LeagueTableRepository::class);
        $this->teamService = app(TeamService::class);
        $this->leagueService = new LeagueService(
            $this->leagueRepository,
            $this->teamService
        );
        $this->seed(TeamsSeeder::class);
    }

    public function testGetChampionshipByWeek()
    {
        $this->leagueService->checkLeagueTable(1);
        $this->leagueService->checkLeagueTable(2); 
        $result = $this->leagueService->getChampionShipByWeek(2);
        $this->assertCount(4, $result);
        foreach ($result as $teamStat) {
            $this->assertIsString($teamStat->name);
            $this->assertIsNumeric($teamStat->value);
            
        }
    }

    protected function tearDown(): void
    {
        DB::statement('PRAGMA foreign_keys=OFF;');
        parent::tearDown();
    }
}