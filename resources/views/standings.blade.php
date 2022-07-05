<x-layout>
    <div class="flex items-stretch justify-between gap-4 lg:divide-x divide-slate-700">
        <div class="w-2/3 px-4">
            <h1 class="text-2xl font-bold leading-tight mb-3">
                {{ __('Leaderboard') }}
            </h1>
            <table class="w-full text-sm text-left text-slate-400">
                <thead class="text-xs uppercase bg-slate-700 text-slate-400">
                <tr>
                    <th scope="col" class="p-3">
                        #
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Played
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Won/Drawn/Lost
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Goal Difference
                    </th>
                    <th scope="col" class="px-6 py-3 text-right">
                        Points
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($teams->sortByDesc('points') as $team)
                    <tr class="border-b bg-slate-800 border-slate-700 odd:bg-slate-800 even:bg-slate-700">
                        <th scope="row" class="p-3">{{$loop->iteration}}</th>
                        <th scope="row" class="px-6 py-4 font-medium text-slate-50 whitespace-nowrap">
                            {{$team->name}}
                        </th>
                        <td class="px-6 py-4">
                            {{$team->played}}
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{$team->won}}/{{$team->drawn}}/{{$team->lost}}
                        </td>
                        <td class="px-6 py-4">
                            {{$team->goal_diff}}
                        </td>
                        <td class="px-6 py-4 text-right">
                            {{$team->points}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="w-1/3 px-4">
            @if($previous->count())
                <h1 class="text-2xl font-bold leading-tight mb-3">
                    Last week's results
                </h1>
                @foreach($previous as $week => $games)
                    <div class="bg-slate-900 border border-slate-700 rounded-lg shadow-lg">
                        <div class="px-4 py-2 font-bold">Week {{$week}}</div>
                        @foreach($games as $game)
                            <div class="grid grid-cols-3 even:bg-slate-700 odd:bg-slate-600/20 p-2">
                                <div class="home text-left truncate">
                                    {{$game->home->name}}
                                </div>
                                <div class="results text-center">
                                    {{$game->home_goals}} - {{$game->away_goals}}
                                </div>
                                <div class="away text-right truncate">
                                    {{$game->away->name}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endif
            <h1 class="text-2xl font-bold leading-tight mt-6 mb-3">
                Coming up
            </h1>
            @if($comingUp->count())
                @foreach($comingUp as $week => $games)
                    <div class="bg-slate-900 border border-slate-700 rounded-lg shadow-lg">
                        <div class="px-4 py-2 font-bold">Week {{$week}}</div>
                        @foreach($games as $game)
                            <div class="grid grid-cols-3 even:bg-slate-700 odd:bg-slate-600/20 p-2">
                                <div class="home text-left truncate">
                                    {{$game->home->name}}
                                </div>
                                <div class="results text-center">
                                    {{$game->home_goals}} - {{$game->away_goals}}
                                </div>
                                <div class="away text-right truncate">
                                    {{$game->away->name}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                <form action="{{route('simulation')}}" method="POST" class="flex items-center justify-center my-6">
                    @csrf
                    <button type="submit" name="week" value="next"
                            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        Simulate Next Week
                    </button>
                    <button type="submit" name="week" value="all"
                            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        Simulate All Weeks
                    </button>
                </form>
            @else
                <div class="bg-slate-900 border py-2 border-slate-700 text-center rounded-lg my-6 shadow-lg">
                    <div class="px-4 pt-2 font-bold">No games coming up</div>
                    @if(\App\Services\SimulationService::isLeagueFinished())
                        <div class="px-4 font-slate-600 text-sm">League is finished</div>
                    @endif
                    <div class="flex items-center justify-center text-xs mt-3">
                        <form action="{{route('fixture.generate')}}" method="post">
                            @csrf
                            <button
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                Generate Fixtures
                            </button>
                        </form>
                        <form action="{{route('fixture.reset')}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                Reset Fixtures
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            @if($predictions)
                <h1 class="text-2xl font-bold leading-tight mt-6 mb-3">
                    Championship Chances
                </h1>
                <div class="bg-slate-900 border border-slate-700 rounded-lg shadow-lg">
                    <div class="grid grid-cols-3 p-3 font-extrabold text-slate-500 uppercase text-xs">
                        <div>Team</div>
                        <div class="text-center">Strength</div>
                        <div class="text-right">Chance</div>
                    </div>
                    @foreach($predictions as $prediction)
                        <div class="grid grid-cols-3 even:bg-slate-700 odd:bg-slate-600/20 p-2 text-sm">
                            <div class="truncate whitespace-normal">{{$prediction->name}}</div>
                            <div class="text-center">{{number_format($prediction->strength,2)}}</div>
                            <div class="text-right">{{number_format($prediction->championship_chance)}}%</div>
                        </div>
                    @endforeach
                </div>
            @endif


        </div>
    </div>
</x-layout>
