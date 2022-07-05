<x-layout>


    @if($fixture->count())
        <div class="my-6 items-center justify-between flex">

            <form action="{{route('simulation')}}" method="POST" class="flex items-center">
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
            <div class="flex items-center">
                <form action="{{route('fixture.generate')}}" method="post">
                    @csrf
                    <button
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Generate Fixtures
                    </button>
                </form>
                <form action="{{route('fixture.reset')}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                        Reset Fixtures
                    </button>
                </form>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-6">
            @foreach($fixture as $week => $games)
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
        </div>

    @else
        <div class="flex flex-col items-center justify-center mx-auto my-6">
            <h1 class="text-2xl font-bold leading-tight mb-3">
                No Scheduled Fixtures Yet
            </h1>
            <form action="{{route('fixture.generate')}}" method="post">
                @csrf
                <button
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Generate Fixtures
                </button>
            </form>
        </div>
    @endif

</x-layout>
