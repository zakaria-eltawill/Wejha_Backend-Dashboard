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
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .subtitle {
            color: #FF4900;
            font-size: 14px;
            margin-top: 5px;
        }
        .meta-info {
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
        .stats-box {
            background-color: #fff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 30px;
            border: 1px solid #00389E;
        }
        .stats-box table {
            width: 100%;
        }
        .stats-box td {
            text-align: center;
            width: 33%;
        }
        .stat-val {
            font-size: 22px;
            font-weight: bold;
            color: #001F8F;
        }
        .stat-lbl {
            font-size: 12px;
            color: #666;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .data-table th {
            background-color: #001F8F;
            color: #fff;
            padding: 12px;
            font-size: 14px;
            text-align: right;
        }
        .data-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #e6f4ea;
            color: #137333;
        }
        .badge-danger {
            background-color: #fce8e6;
            color: #c5221f;
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
                    <div class="title">{{ $event->title_ar }}</div>
                    <div class="subtitle">{{ $event->title_en }}</div>
                </td>
                <td style="text-align: left; vertical-align: middle;">
                    <img src="{{ public_path('assets/logo/wejha_logo_vertical_multi_gradient_transparent.png') }}" class="logo" alt="Wejha Logo">
                </td>
            </tr>
        </table>
    </div>

    <div class="meta-info">
        <strong>تاريخ التقرير:</strong> {{ $generated_at->format('Y-m-d H:i') }} | 
        <strong>موقع الفعالية:</strong> {{ $event->venue }} |
        <strong>تاريخ الفعالية:</strong> {{ $event->event_date->format('Y-m-d') }}
    </div>

    <div class="stats-box">
        <table>
            <tr>
                <td>
                    <div class="stat-val">{{ $event->capacity }}</div>
                    <div class="stat-lbl">السعة الإجمالية</div>
                </td>
                <td>
                    <div class="stat-val">{{ $event->registrations->count() }}</div>
                    <div class="stat-lbl">عدد المسجلين</div>
                </td>
                <td>
                    <div class="stat-val">
                        {{ $event->registrations->where('status', \App\Enums\RegistrationStatus::CHECKED_IN)->count() }}
                    </div>
                    <div class="stat-lbl">عدد الحاضرين</div>
                </td>
            </tr>
        </table>
    </div>

    <h3 style="color: #001F8F; margin-bottom: 15px;">قائمة حضور المشاركين</h3>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>اسم المشارك</th>
                <th>البريد الإلكتروني</th>
                <th>المدرسة</th>
                <th>حالة الحضور</th>
                <th>وقت التحضير</th>
            </tr>
        </thead>
        <tbody>
            @php $index = 1; @endphp
            @foreach($event->registrations as $reg)
                <tr>
                    <td>{{ $index++ }}</td>
                    <td>{{ $reg->user->name }}</td>
                    <td>{{ $reg->user->email }}</td>
                    <td>{{ $reg->user->school_name ?? '-' }}</td>
                    <td>
                        @if($reg->status === \App\Enums\RegistrationStatus::CHECKED_IN)
                            <span class="badge badge-success">حاضر</span>
                        @else
                            <span class="badge badge-danger">غائب</span>
                        @endif
                    </td>
                    <td>
                        {{ $reg->attendance ? $reg->attendance->scan_time->format('H:i:s') : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="slogan">حسن اختيارك هو بداية مشوارك | خلي ديما عندك وجهة</div>
        <div class="hashtag">#وجهتك-تبدأ-من-هنا</div>
        <div style="margin-top: 10px;">تم توليد هذا التقرير تلقائياً بواسطة منصة وجهة الرقمية 2026</div>
    </div>

</body>
</html>
