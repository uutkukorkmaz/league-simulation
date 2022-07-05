<?php


return [
    'name' => 'Premiere League Simulation',
    'morale_factor' => 0.1,
    'teams' => [
        'Arsenal',
        'Aston Villa',
        'Chelsea',
        'Everton',
        'Liverpool',
        'Manchester City',
        'Manchester United',
        'Newcastle United',
        'Southampton',
        'Tottenham Hotspur',
    ],

    'fixture' => [
        'allowRevenges' => true,
    ],
    'rules' => [
        'points' => [
            'win' => 3,
            'draw' => 1,
            'loss' => 0,
        ],
        'strength' => [
            'win' => .75,
            'draw' => .5,
            'loss' => -.5,
            'goals' => [
                'for' => [
                    'away' => .25,
                    'home' => .125,
                ],
                'against' => [
                    'away' => -.075,
                    'home' => -.125,
                ],
            ],
        ],
    ],

];
