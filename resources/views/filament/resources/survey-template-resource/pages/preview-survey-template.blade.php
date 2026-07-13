<x-filament-panels::page>
    <div dir="rtl" style="max-width: 720px; margin: 0 auto; background: #F1F2F8; padding: 24px; border-radius: 16px;">
        {{-- Survey header card --}}
        <div style="background: linear-gradient(160deg, #001F8F 0%, #0033CC 100%); border-radius: 12px; padding: 28px 24px; margin-bottom: 20px; border-top: 6px solid #FF4900; box-shadow: 0 4px 14px rgba(0,31,143,0.18);">
            <h1 style="color: #ffffff; font-size: 1.5rem; font-weight: 700; margin: 0 0 6px;">
                {{ $record->name_ar }}
            </h1>
            @if($record->name_en)
                <p style="color: #d6ddfb; font-size: 0.9rem; margin: 0 0 12px;">{{ $record->name_en }}</p>
            @endif
            @if($record->description_ar)
                <p style="color: #eef1ff; font-size: 0.95rem; margin: 0;">{{ $record->description_ar }}</p>
            @endif
        </div>

        @if($record->questions->isEmpty())
            <div style="text-align: center; padding: 48px 20px; color: #9ca3af; background: #ffffff; border: 1px dashed #d1d5db; border-radius: 12px;">
                لا توجد أسئلة بعد. أضف أسئلة من صفحة التعديل لتظهر هنا. / No questions yet. Add questions from the edit page to see them here.
            </div>
        @endif

        {{-- Question cards --}}
        @foreach($record->questions as $index => $question)
            <div style="background: #ffffff; border: 1px solid #E4E7F5; border-right: 4px solid #FF4900; border-radius: 10px; padding: 20px 22px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,31,143,0.07);">
                <div style="display: flex; align-items: baseline; gap: 8px; margin-bottom: 4px;">
                    <span style="font-weight: 700; color: #001F8F; font-size: 0.95rem;">{{ $index + 1 }}.</span>
                    <span style="font-weight: 700; font-size: 1rem; color: #1F2937;">{{ $question->question_text_ar }}</span>
                    @if($question->is_required)
                        <span style="color: #FF4900;">*</span>
                    @endif
                </div>

                @if($question->question_text_en)
                    <p style="color: #9ca3af; font-size: 0.85rem; margin: 0 0 8px;">{{ $question->question_text_en }}</p>
                @endif

                @if($question->description_ar)
                    <p style="color: #6b7280; font-size: 0.875rem; margin: 4px 0 12px;">{{ $question->description_ar }}</p>
                @endif

                <div style="margin-top: 12px; pointer-events: none;">
                    @switch($question->type->value)
                        @case('text')
                        @case('email')
                        @case('phone')
                        @case('number')
                            <input type="text" disabled placeholder="{{ $question->help_text_ar ?? 'إجابة الطالب هنا / Student answer here' }}"
                                style="width: 100%; max-width: 420px; border: none; border-bottom: 1px solid #D1D5DB; padding: 6px 2px; background: transparent; color: #6b7280; font-size: 0.9rem;">
                            @break

                        @case('textarea')
                            <textarea disabled rows="2" placeholder="{{ $question->help_text_ar ?? 'إجابة الطالب هنا / Student answer here' }}"
                                style="width: 100%; border: 1px solid #D1D5DB; border-radius: 6px; padding: 8px 10px; background: #F9FAFC; color: #6b7280; font-size: 0.9rem; resize: none;"></textarea>
                            @break

                        @case('date')
                            <input type="text" disabled placeholder="YYYY-MM-DD"
                                style="width: 200px; border: 1px solid #D1D5DB; border-radius: 6px; padding: 6px 10px; background: #F9FAFC; color: #6b7280; font-size: 0.9rem;">
                            @break

                        @case('rating')
                            <div style="font-size: 1.4rem; letter-spacing: 4px; color: #FBBF24;">☆☆☆☆☆</div>
                            @break

                        @case('multiple_choice')
                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                @forelse(($question->options ?? []) as $option)
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.9rem; color: #374151;">
                                        <input type="radio" disabled style="accent-color: #001F8F;">
                                        <span>{{ is_array($option) ? ($option['value'] ?? '') : $option }}</span>
                                    </label>
                                @empty
                                    <span style="color: #DC2626; font-size: 0.85rem;">⚠ لم تُضف أي خيارات بعد / No options added yet</span>
                                @endforelse
                            </div>
                            @break

                        @case('checkbox')
                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                @forelse(($question->options ?? []) as $option)
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.9rem; color: #374151;">
                                        <input type="checkbox" disabled style="accent-color: #001F8F;">
                                        <span>{{ is_array($option) ? ($option['value'] ?? '') : $option }}</span>
                                    </label>
                                @empty
                                    <span style="color: #DC2626; font-size: 0.85rem;">⚠ لم تُضف أي خيارات بعد / No options added yet</span>
                                @endforelse
                            </div>
                            @break
                    @endswitch
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
