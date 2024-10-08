<?php

namespace App\Services;

use App\Implementations\MatchRepository;
use Illuminate\Database\Eloquent\Collection;

class MatchService
{

    protected $matchRepository;
    protected $teamService;
    public function __construct(MatchRepository $matchRepository, TeamService $teamService)
    {
        $this->matchRepository = $matchRepository;
        $this->teamService = $teamService;
    }

    public function calculateScore(array $match_couple): array
    {
        $home_score = $match_couple[0]['strength'];
        $away_score = $match_couple[1]['strength'];

        $home_strength = intval(sqrt($match_couple[0]['strength']) / 2);
        $away_strength = intval(sqrt($match_couple[1]['strength']) / 2);

        $home = $home_strength;
        $away = $away_strength;


        if ($home_score > $away_score) {
            $home += 1;
        } elseif ($home_score < $away_score) {
            $away += 1;
        }

        return ['home' => $home, 'away' => $away];
    }



    public function createRandomMatch(array $match_couple, int $week): void
    {
        $scoring = $this->calculateScore($match_couple);
        $this->matchRepository->create([
            'home_team_id' => $match_couple[0]['id'],
            'away_team_id' => $match_couple[1]['id'],
            'home_score' => $scoring['home'],
            'away_score' => $scoring['away'],
            'week' => $week
        ]);
    }

    public function createOrGetMatchByWeek($week): Collection
    {
        $teams = $this->teamService->getAllTeams()->toArray();
        $matchesByWeek = $this->matchRepository->findByWeek($week);
        if ($matchesByWeek->count() == 0) {
            shuffle($teams);
            $random_match_array = array_chunk($teams, 2);
            foreach ($random_match_array as $key => $match_couple) {
                $this->createRandomMatch($match_couple, $week);

            }
            $matchesByWeek = $this->matchRepository->findByWeek($week);
        }

        return $matchesByWeek;
    }

}
;