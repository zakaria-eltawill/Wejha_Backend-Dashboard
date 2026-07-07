<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>{{ $title_ar }}</title>
    <style>
        body {
            font-family: 'cairo', 'DejaVu Sans', sans-serif;
            background-color: #F1F2F2;
            color: #333;
            margin: 0;
            padding: 20px;
            direction: rtl;
            text-align: right;
        }
        .header {
            border-bottom: 3px solid #001F8F;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header table {
            width: 100%;
        }
        .logo {
            max-width: 150px;
        }
        .title {
            color: #001F8F;
            font-size: 22px;
            font-weight: bold;
            margin: 0;
        }
        .subtitle {
            color: #FF4900;
            font-size: 13px;
            margin-top: 5px;
        }
        .meta-info {
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
        .response-section {
            background-color: #fff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-right: 4px solid #FF4900;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .question-text {
            font-weight: bold;
            color: #001F8F;
            font-size: 15px;
            margin-bottom: 10px;
        }
        .answers-list {
            margin-right: 15px;
            font-size: 13px;
        }
        .answer-item {
            padding: 5px 0;
            border-bottom: 1px dashed #eee;
        }
        .answer-item:last-child {
            border-bottom: none;
        }
        .footer {
            margin-top: 50px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            text-align: center;
            font-size: 11px;
            color: #777;
        }
        .slogan {
            color: #00389E;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 5px;
        }
        .hashtag {
            color: #FF4900;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td style="text-align: right; vertical-align: middle;">
                    <div class="title">{{ $title_ar }}</div>
                    <div class="subtitle">{{ $title_en }}</div>
                </td>
                <td style="text-align: left; vertical-align: middle;">
                    <img src="{{ public_path('assets/logo/wejha_logo_vertical_multi_gradient_transparent.png') }}" class="logo" alt="Wejha Logo">
                </td>
            </tr>
        </table>
    </div>

    <div class="meta-info">
        <strong>تاريخ التقرير:</strong> {{ $generated_at->format('Y-m-d H:i') }} | 
        <strong>الفعالية:</strong> {{ $evaluation->event->title_ar }} |
        <strong>نوع التقييم:</strong> {{ $evaluation->evaluation_type->labelAr() }} |
        <strong>النموذج المستخدم:</strong> {{ $evaluation->template->name_ar }}
    </div>

    <h3 style="color: #001F8F; margin-bottom: 20px; border-bottom: 1px solid #001F8F; padding-bottom: 5px;">إجابات المشاركين حسب الأسئلة</h3>

    @foreach($evaluation->template->questions as $question)
        <div class="response-section">
            <div class="question-text">س: {{ $question->question_text_ar }} ({{ $question->question_text_en }})</div>
            <div class="answers-list">
                @php
                    $qResponses = $evaluation->responses->where('question_id', $question->id);
                @endphp
                @if($qResponses->isEmpty())
                    <div style="color: #999; font-style: italic;">لا توجد إجابات على هذا السؤال بعد.</div>
                @else
                    @foreach($qResponses as $resp)
                        <div class="answer-item">
                            <strong>{{ $resp->user->name }}:</strong> 
                            @if($question->type->value === 'checkbox')
                                {{ $resp->response_json ? implode(', ', $resp->response_json) : '-' }}
                            @else
                                {{ $resp->response_text ?? '-' }}
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endforeach

    <div class="footer">
        <div class="slogan">حسن اختيارك هو بداية مشوارك | خلي ديما عندك وجهة</div>
        <div class="hashtag">#وجهتك-تبدأ-من-هنا</div>
        <div style="margin-top: 10px;">تم توليد هذا التقرير تلقائياً بواسطة منصة وجهة الرقمية 2026</div>
    </div>

</body>
</html>
