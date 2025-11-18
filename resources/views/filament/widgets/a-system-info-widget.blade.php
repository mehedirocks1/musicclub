<x-filament-widgets::widget>
    <x-filament::card class="p-4 w-full">
        <div class="flex flex-wrap items-center justify-start gap-6 w-full">
            {{-- User --}}
            <div class="flex items-center space-x-2 bg-blue-50 rounded-lg px-4 py-2 flex-1 min-w-[200px]">
                <span class="text-2xl text-blue-600">👤</span>
                <span class="font-semibold text-blue-700">Welcome: {{ $userName }}</span>
            </div>

            {{-- Date --}}
            <div class="flex items-center space-x-2 bg-green-50 rounded-lg px-4 py-2 flex-1 min-w-[200px]">
                <span class="text-2xl text-green-600">📅</span>
                <span class="font-semibold text-green-700">Today: {{ $currentDate }}</span>
            </div>

            {{-- Time --}}
            <div class="flex items-center space-x-2 bg-yellow-50 rounded-lg px-4 py-2 flex-1 min-w-[200px]">
                <span class="text-2xl text-yellow-600">⏰</span>
                <span class="font-semibold text-yellow-700">Time (GMT+6): {{ $currentTime }}</span>
            </div>

            {{-- PHP --}}
            <div class="flex items-center space-x-2 bg-indigo-50 rounded-lg px-4 py-2 flex-1 min-w-[200px]">
                <span class="text-2xl text-indigo-600">💻</span>
                <span class="font-semibold text-indigo-700">PHP Version: {{ $phpVersion }}</span>
            </div>

            {{-- Laravel --}}
            <div class="flex items-center space-x-2 bg-pink-50 rounded-lg px-4 py-2 flex-1 min-w-[200px]">
                <span class="text-2xl text-pink-600">🚀</span>
                <span class="font-semibold text-pink-700">Laravel Version: {{ $laravelVersion }}</span>
            </div>

            {{-- OS --}}
            <div class="flex items-center space-x-2 bg-gray-50 rounded-lg px-4 py-2 flex-1 min-w-[200px]">
                <span class="text-2xl text-gray-600">🖥️</span>
                <span class="font-semibold text-gray-700">Server OS: {{ $serverOs }}</span>
            </div>
        </div>
    </x-filament::card>
</x-filament-widgets::widget>
