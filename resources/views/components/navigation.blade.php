<div
    class="text-sm mb-6 font-medium flex items-end justify-between text-center text-gray-500 border-b border-gray-200 text-gray-400 border-gray-700">
    <div>
        <h1 class="text-2xl mb-2">League Simulator</h1>
    </div>
    <ul class="flex flex-wrap -mb-px">
        <x-nav-item :href="route('standings')" :title="__('Standings')" :active="\Route::is('standings')"/>
        <x-nav-item :href="route('fixture.index')" :title="__('Fixtures')" :active="\Route::is('fixture.index')"/>
    </ul>
</div>
