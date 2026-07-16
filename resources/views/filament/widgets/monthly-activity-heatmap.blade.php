<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                {{ __('filament-widgets.monthly_heatmap.heading') }}
            </h2>
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('filament-widgets.monthly_heatmap.subheading') }}</span>
        </div>

        <div class="flex flex-col items-center justify-center p-5 bg-white dark:bg-gray-900 rounded-lg border border-gray-100 dark:border-gray-800">
            {{-- Heatmap grid: sequential one-hue (Wejha blue) ramp, light -> dark = low -> high activity --}}
            <div class="grid grid-cols-7 gap-2 md:gap-2.5 justify-center w-full max-w-lg">
                @foreach($dates as $dateStr => $data)
                    @php
                        $intensity = $data['intensity'];
                        // Sequential blue ramp (steps 100/250/400/550/700 from the validated palette),
                        // light -> dark = low -> high. 0 falls back to a neutral surface, never the hue.
                        [$bg, $text] = match (true) {
                            $intensity <= 0 => ['#f3f4f6', '#9ca3af'],
                            $intensity === 1 => ['#cde2fb', '#184f95'],
                            $intensity === 2 => ['#86b6ef', '#0d366b'],
                            $intensity === 3 => ['#3987e5', '#ffffff'],
                            $intensity === 4 => ['#1c5cab', '#ffffff'],
                            default => ['#0d366b', '#ffffff'],
                        };
                    @endphp

                    <div
                        class="flex flex-col items-center justify-center rounded-md aspect-square text-center transition-transform duration-150 hover:scale-110 hover:shadow-md hover:z-10 cursor-default"
                        style="background-color: {{ $bg }}; color: {{ $text }};"
                        title="{{ $dateStr }}: {{ $intensity }} {{ __('filament-widgets.monthly_heatmap.tooltip_suffix') }}"
                    >
                        <span class="text-[11px] font-bold leading-tight">{{ $data['label'] }}</span>
                        @if($intensity > 0)
                            <span class="text-[10px] font-semibold opacity-90 leading-tight">{{ $intensity }}</span>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Legend: same ramp steps used in the grid above, so it's a literal key, not decoration --}}
            <div class="flex items-center justify-center w-full max-w-lg mt-5 gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                <span>{{ __('filament-widgets.monthly_heatmap.legend_less') }}</span>
                <div class="w-3.5 h-3.5 rounded" style="background-color: #f3f4f6;"></div>
                <div class="w-3.5 h-3.5 rounded" style="background-color: #cde2fb;"></div>
                <div class="w-3.5 h-3.5 rounded" style="background-color: #86b6ef;"></div>
                <div class="w-3.5 h-3.5 rounded" style="background-color: #3987e5;"></div>
                <div class="w-3.5 h-3.5 rounded" style="background-color: #1c5cab;"></div>
                <div class="w-3.5 h-3.5 rounded" style="background-color: #0d366b;"></div>
                <span>{{ __('filament-widgets.monthly_heatmap.legend_more') }}</span>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
