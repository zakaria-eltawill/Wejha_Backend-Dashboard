<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>{{ $title_ar }}</title>
    <style>
        @font-face {
            font-family: 'DINNextLTArabic';
            src: url('{{ public_path("assets/fonts/DINNextLTArabic-Regular.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'DINNextLTArabic';
            src: url('{{ public_path("assets/fonts/DINNextLTArabic-Bold.ttf") }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        body {
            font-family: 'DINNextLTArabic', sans-serif;
            background-color: #ffffff;
            color: #1f2937;
            margin: 0;
            padding: 10px;
            direction: ltr;
            text-align: right;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .title {
            color: #001F8F;
            font-size: 20px;
            font-weight: bold;
            margin: 0;
            line-height: 1.3;
            text-align: right;
        }

        .subtitle {
            color: #FF4900;
            font-size: 11px;
            font-weight: bold;
            margin-top: 4px;
            text-transform: uppercase;
            text-align: right;
        }

        .logo {
            height: 50px;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 10px;
            color: #4b5563;
            background-color: #f9fafb;
            border: 1px solid #f3f4f6;
            border-radius: 6px;
            padding: 8px 12px;
        }

        .meta-label {
            font-weight: bold;
            color: #1f2937;
        }

        .section-title {
            color: #001F8F;
            font-size: 13px;
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 15px;
            border-right: 3px solid #FF4900;
            padding-right: 8px;
            text-align: right;
        }

        .response-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-right: 4px solid #FF4900;
            text-align: right;
        }

        .question-text {
            font-weight: bold;
            color: #001F8F;
            font-size: 12px;
            margin-bottom: 8px;
            text-align: right;
        }

        .answers-list {
            font-size: 10px;
            color: #374151;
            text-align: right;
        }

        .answer-item {
            padding: 6px 0;
            border-bottom: 1px dashed #e2e8f0;
            text-align: right;
        }

        .answer-item:last-child {
            border-bottom: none;
        }

        .participant-name {
            font-weight: bold;
            color: #1f2937;
            margin-left: 5px;
        }

        .footer {
            margin-top: 40px;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
        }

        .slogan {
            color: #001F8F;
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .hashtag {
            color: #FF4900;
            font-weight: bold;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>

    <!-- Header Section (Visually RTL in LTR mode) -->
    <table class="header-table">
        <tr>
            <td style="text-align: left; vertical-align: middle;" width="30%">
                <img src="{{ $logo_base64 }}" class="logo" alt="Wejha Logo">
            </td>
            <td style="text-align: right; vertical-align: middle;" width="70%">
                <div class="title">{{ $title_ar }}</div>
                <div class="subtitle">{{ $title_en }}</div>
            </td>
        </tr>
    </table>

    <!-- Metadata Section (Visually RTL in LTR mode) -->
    <table class="meta-table">
        <tr>
            <td width="25%" style="text-align: left;">
                <span>{{ $generated_at->format('Y-m-d H:i') }}</span> <span class="meta-label">:تاريخ التقرير</span>
            </td>
            <td width="25%" style="text-align: center;">
                <span>{{ $evaluation->template->name_ar }}</span> <span class="meta-label">:النموذج</span>
            </td>
            <td width="20%" style="text-align: center;">
                <span>{{ $evaluation->evaluation_type->labelAr() }}</span> <span class="meta-label">:نوع التقييم</span>
            </td>
            <td width="30%" style="text-align: right;">
                <span>{{ $evaluation->event->title_ar }}</span> <span class="meta-label">:الفعالية</span>
            </td>
        </tr>
    </table>

    <!-- List Section Header -->
    <div class="section-title">إجابات المشاركين حسب الأسئلة / Survey Submissions by Question</div>

    @forelse($evaluation->template->questions as $question)
        <div class="response-card">
            <div class="question-text">س: {{ $question->question_text_ar }} ({{ $question->question_text_en }})</div>
            <div class="answers-list">
                @php
                    $qResponses = $evaluation->responses->where('question_id', $question->id);
                @endphp
                @if($qResponses->isEmpty())
                    <div style="color: #9ca3af; font-style: italic; padding: 5px 0;">لا توجد إجابات على هذا السؤال بعد.</div>
                @else
                    @foreach($qResponses as $resp)
                        <div class="answer-item">
                            <span>
                                @if($question->type->value === 'checkbox')
                                    {{ $resp->response_json ? implode(', ', $resp->response_json) : '-' }}
                                @else
                                    {{ $resp->response_text ?? '-' }}
                                @endif
                            </span>
                            <span class="participant-name">:{{ $resp->user->name }}</span> 
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @empty
        <div style="text-align: center; color: #9ca3af; padding: 30px;">
            لا توجد أسئلة مضافة في هذا النموذج بعد.
        </div>
    @endforelse

    <!-- Footer Section -->
    <div class="footer">
        <div class="slogan">حسن اختيارك هو بداية مشوارك | خلي ديما عندك وجهة</div>
        <div class="hashtag">#وجهتك_تبدأ_من_هنا</div>
        <div style="margin-top: 5px;">تم توليد هذا التقرير تلقائياً بواسطة منصة وجهة الرقمية 2026</div>
    </div>

</body>
</html>
