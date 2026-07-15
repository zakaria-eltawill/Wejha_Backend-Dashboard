<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                {{ __('filament-widgets.monthly_heatmap.heading') }}
            </h2>
            <span class="text-xs text-gray-500">{{ __('filament-widgets.monthly_heatmap.subheading') }}</span>
        </div>

        <div class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-900 rounded-lg border border-gray-100 dark:border-gray-800">
            <!-- Heatmap Grid -->
            <div class="grid grid-cols-7 gap-2 md:gap-3 justify-center w-full max-w-lg">
                @foreach($dates as $dateStr => $data)
                    @php
                        $colorClass = 'bg-gray-100 dark:bg-gray-800'; // 0
                        if ($data['intensity'] === 1) {
                            $colorClass = 'bg-blue-200 text-blue-900 dark:bg-blue-900/40 dark:text-blue-200';
                        } elseif ($data['intensity'] === 2) {
                            $colorClass = 'bg-blue-400 text-white dark:bg-blue-700';
                        } elseif ($data['intensity'] >= 3) {
                            $colorClass = 'bg-blue-700 text-white'; // #001F8F equivalent
                        }
                    @endphp
                    
                    <div 
                        class="flex flex-col items-center justify-center p-2 rounded-md aspect-square text-center transition-all hover:scale-105 cursor-pointer {{ $colorClass }}"
                        title="{{ $dateStr }}: {{ $data['intensity'] }} {{ __('filament-widgets.monthly_heatmap.tooltip_suffix') }}"
                    >
                        <span class="text-xs font-bold">{{ $data['label'] }}</span>
                        @if($data['intensity'] > 0)
                            <span class="text-[10px] opacity-90">({{ $data['intensity'] }})</span>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Legend -->
            <div class="flex items-center justify-end w-full max-w-lg mt-4 gap-2 text-xs text-gray-500">
                <span>{{ __('filament-widgets.monthly_heatmap.legend_less') }}</span>
                <div class="w-3 h-3 rounded bg-gray-100 dark:bg-gray-800"></div>
                <div class="w-3 h-3 rounded bg-blue-200 dark:bg-blue-900/40"></div>
                <div class="w-3 h-3 rounded bg-blue-400 dark:bg-blue-700"></div>
                <div class="w-3 h-3 rounded bg-blue-700"></div>
                <span>{{ __('filament-widgets.monthly_heatmap.legend_more') }}</span>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
