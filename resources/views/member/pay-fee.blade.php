<x-member-layout>
    <h2 class="text-2xl font-bold mb-4">Pay Fee</h2>

    {{ $this->form }}

    <div class="mt-4">
        <button type="button" wire:click="save" class="px-4 py-2 bg-amber-500 text-white rounded hover:bg-amber-600 transition">
            Pay Fee
        </button>
    </div>
</x-member-layout>
