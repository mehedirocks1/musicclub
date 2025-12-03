<x-filament-panels::page>
    <div class="space-y-6">

        <h2 class="text-xl font-bold">Backup Options</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Website Backup --}}
            <div class="p-4 border rounded shadow-sm">
                <h3 class="font-semibold mb-2">Website Backup</h3>
                <p>Backup all website files.</p>

                <x-filament::button wire:click="backupWebsite" color="primary">
                    Backup Website
                </x-filament::button>

                @if($websiteProgress > 0)
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 h-3 rounded">
                            <div class="bg-primary-600 h-3 rounded" style="width: {{ $websiteProgress }}%;"></div>
                        </div>
                        <p class="text-sm mt-1">{{ $websiteProgress }}% Completed</p>
                        <p class="text-sm mt-1 text-gray-600">{{ $statusMessage }}</p>
                    </div>
                @endif
            </div>

            {{-- Database Backup --}}
            <div class="p-4 border rounded shadow-sm">
                <h3 class="font-semibold mb-2">Database Backup</h3>
                <p>Backup your database.</p>

                <x-filament::button wire:click="backupDatabase" color="secondary">
                    Backup Database
                </x-filament::button>

                @if($databaseProgress > 0)
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 h-3 rounded">
                            <div class="bg-green-600 h-3 rounded" style="width: {{ $databaseProgress }}%;"></div>
                        </div>
                        <p class="text-sm mt-1">{{ $databaseProgress }}% Completed</p>
                        <p class="text-sm mt-1 text-gray-600">{{ $statusMessage }}</p>
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-filament-panels::page>
