<x-filament-panels::page>
    <div 
        x-data="{ 
            isSending: false,
            progress: 0, 
            total: 0, 
            sentCount: 0,
            failedCount: 0,
            
            async startSending() {
                this.isSending = true;
                this.progress = 0;
                this.sentCount = 0;
                this.failedCount = 0;

                // 1. Ask PHP to prepare the list
                const result = await $wire.prepareNumbers();
                
                if (!result || result.count === 0) {
                    this.isSending = false;
                    return;
                }

                this.total = result.count;
                
                // 2. Client-side loop to trigger PHP calls one by one
                for (let i = 0; i < this.total; i++) {
                    let status = await $wire.sendSingleSms(i);
                    
                    if(status === 'sent') this.sentCount++;
                    else this.failedCount++;

                    this.progress = Math.round(((i + 1) / this.total) * 100);
                }

                // 3. Done
                this.isSending = false;
                $wire.sendingComplete();
            }
        }"
    >

        <div class="space-y-4" :class="{ 'opacity-50 pointer-events-none': isSending }">
            {{ $this->form }}
        
            <div class="flex justify-end">
                <x-filament::button 
                    size="lg" 
                    icon="heroicon-m-paper-airplane"
                    @click="startSending"
                    wire:loading.attr="disabled"
                >
                    Start Sending
                </x-filament::button>
            </div>
        </div>

        {{-- Progress Bar UI --}}
        <div x-show="isSending || progress > 0" x-transition class="mt-6 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between mb-2 text-sm font-medium">
                <span>Progress</span>
                <span x-text="progress + '%'"></span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 h-4 rounded-full overflow-hidden">
                <div class="bg-primary-600 h-4 transition-all duration-300" :style="'width: ' + progress + '%'"></div>
            </div>
            <div class="grid grid-cols-3 gap-4 mt-4 text-center">
                <div class="font-bold text-gray-700 dark:text-gray-200">Total: <span x-text="total"></span></div>
                <div class="font-bold text-green-600">Sent: <span x-text="sentCount"></span></div>
                <div class="font-bold text-red-600">Failed: <span x-text="failedCount"></span></div>
            </div>
        </div>
    </div>
</x-filament-panels::page>