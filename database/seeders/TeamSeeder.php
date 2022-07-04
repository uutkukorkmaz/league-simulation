<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stdout = $this->command->getOutput();
        $teams = config('league.teams');
        $stdout->progressStart(count($teams));
        foreach ($teams as $team) {
            Team::create(['name' => $team]);
            $stdout->progressAdvance();
        }
        $stdout->progressFinish();
    }

}
