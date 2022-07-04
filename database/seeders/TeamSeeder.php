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
        $this->command->withProgressBar(count(config('league.teams')), function ($team) {
            Team::create(['name' => $team]);
        });
    }

}
