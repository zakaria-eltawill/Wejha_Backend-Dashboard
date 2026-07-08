<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Card 1: Attendance Reports -->
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
            <h3 class="text-md font-bold mb-4 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                تقارير حضور الفعاليات / Attendance Reports
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">اختر الفعالية / Select Event</label>
                    <select wire:model="event_id" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-2 text-sm text-gray-700 dark:text-gray-300">
                        <option value="">-- اختر الفعالية --</option>
                        @foreach(\App\Models\Event::all() as $event)
                            <option value="{{ $event->id }}">{{ $event->title_ar }} ({{ $event->event_date->format('Y-m-d') }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button wire:click="downloadAttendancePdf" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition-all flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        تحميل PDF / Download PDF
                    </button>
                    <button wire:click="downloadAttendanceExcel" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg text-xs transition-all flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        تصدير Excel / Export Excel
                    </button>
                </div>
            </div>
        </div>

        <!-- Card 2: Survey & Assessment Reports -->
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
            <h3 class="text-md font-bold mb-4 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.238.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.572-.383-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                تقارير استبيانات التقييم / Survey Reports
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">اختر التقييم / Select Evaluation</label>
                    <select wire:model="evaluation_id" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-2 text-sm text-gray-700 dark:text-gray-300">
                        <option value="">-- اختر التقييم --</option>
                        @foreach(\App\Models\EventEvaluation::with(['event', 'template'])->get() as $eval)
                            <option value="{{ $eval->id }}">
                                {{ $eval->event->title_ar }} - {{ $eval->evaluation_type->labelAr() }} ({{ $eval->template->name_ar }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button wire:click="downloadSurveyPdf" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition-all flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        تحميل PDF / Download PDF
                    </button>
                    <button wire:click="downloadSurveyExcel" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg text-xs transition-all flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        تصدير Excel / Export Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="mt-6">
        <h3 class="text-md font-bold mb-4 text-gray-700 dark:text-gray-300">تحليلات الأداء ومشاركات الطلاب / Charts & Insights</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @livewire(\App\Filament\Widgets\AttendanceRateChart::class)
            @livewire(\App\Filament\Widgets\EventRegistrationsChart::class)
            @livewire(\App\Filament\Widgets\TopSchoolsChart::class)
            @livewire(\App\Filament\Widgets\EventDistributionChart::class)
            @livewire(\App\Filament\Widgets\SurveyRatingsChart::class)
            @livewire(\App\Filament\Widgets\MonthlyActivityHeatmap::class)
        </div>
    </div>
</x-filament-panels::page>
