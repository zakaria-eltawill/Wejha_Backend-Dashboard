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
            height: 100px;
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
            margin-bottom: 12px;
            border-right: 3px solid #FF4900;
            padding-right: 8px;
            text-align: right;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .data-table th {
            background-color: #001F8F;
            color: #ffffff;
            font-weight: bold;
            padding: 8px 10px;
            font-size: 10px;
            text-align: right;
            border: 1px solid #001F8F;
        }

        .data-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
            color: #374151;
            text-align: right;
        }

        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
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
        <!-- Question Section Title -->
        <div class="section-title" style="margin-top: 15px; margin-bottom: 8px; font-size: 11px; color: #FF4900;">
            س: {{ $question->question_text_ar }} ({{ $question->question_text_en }})
        </div>

        <!-- Responses Data Table (Visually RTL in LTR mode) -->
        <table class="data-table">
            <thead>
                <tr>
                    <th width="45%">الإجابة / Answer</th>
                    <th width="15%" style="text-align: center;">التخصص / Track</th>
                    <th width="15%">المدرسة / School</th>
                    <th width="20%" style="text-align: right;">اسم المشارك / Participant Name</th>
                    <th width="5%" style="text-align: center;">#</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $index = 1; 
                    $qResponses = $evaluation->responses->where('question_id', $question->id);
                @endphp
                @forelse($qResponses as $resp)
                    <tr>
                        <td>
                            @if($question->type->value === 'checkbox')
                                {{ $resp->response_json ? implode(', ', $resp->response_json) : '-' }}
                            @else
                                {{ $resp->response_text ?? '-' }}
                            @endif
                        </td>
                        <td style="text-align: center;">{{ $resp->user->specialization ?? '-' }}</td>
                        <td>{{ $resp->user->school_name ?? '-' }}</td>
                        <td style="font-weight: bold; text-align: right;">{{ $resp->user->name }}</td>
                        <td style="text-align: center;">{{ $index++ }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #9ca3af; padding: 12px; font-size: 9px;">
                            لا توجد إجابات على هذا السؤال بعد.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
