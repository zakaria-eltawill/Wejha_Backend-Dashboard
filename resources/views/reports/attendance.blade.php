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

        .stats-table {
            width: 100%;
            margin-bottom: 25px;
            border-spacing: 10px;
            margin-left: -10px;
            margin-right: -10px;
        }

        .stat-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            text-align: center;
            padding: 12px 6px;
        }

        .stat-val {
            font-size: 22px;
            font-weight: bold;
            color: #001F8F;
            margin-bottom: 2px;
        }

        .stat-lbl {
            font-size: 10px;
            color: #64748b;
            font-weight: bold;
        }

        .section-title {
            color: #001F8F;
            font-size: 13px;
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 12px;
            border-right: 3px solid #FF4900;
            padding-right: 8px;
            text-align: right;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
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

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
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
                <img src="{{ public_path('assets/logo/wejha_logo_vertical_multi_gradient_transparent.png') }}" class="logo" alt="Wejha Logo">
            </td>
            <td style="text-align: right; vertical-align: middle;" width="70%">
                <div class="title">{{ $event->title_ar }}</div>
                <div class="subtitle">{{ $event->title_en }}</div>
            </td>
        </tr>
    </table>

    <!-- Metadata Section (Visually RTL in LTR mode) -->
    <table class="meta-table">
        <tr>
            <td width="33%" style="text-align: left;">
                <span class="meta-label">تاريخ التقرير:</span> {{ $generated_at->format('Y-m-d H:i') }}
            </td>
            <td width="33%" style="text-align: center;">
                <span class="meta-label">موقع الفعالية:</span> {{ $event->venue }}
            </td>
            <td width="33%" style="text-align: right;">
                <span class="meta-label">تاريخ الفعالية:</span> {{ \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') }}
            </td>
        </tr>
    </table>

    <!-- Statistics Section (Visually RTL in LTR mode) -->
    <table class="stats-table">
        <tr>
            <td width="25%">
                <div class="stat-card">
                    <div class="stat-val">
                        @php
                            $totalReg = $event->registrations->count();
                            $attended = $event->registrations->where('status', \App\Enums\RegistrationStatus::CHECKED_IN)->count();
                            $rate = $totalReg > 0 ? round(($attended / $totalReg) * 100, 1) : 0;
                        @endphp
                        {{ $rate }}%
                    </div>
                    <div class="stat-lbl">نسبة الحضور / Attendance Rate</div>
                </div>
            </td>
            <td width="25%">
                <div class="stat-card">
                    <div class="stat-val">
                        {{ $event->registrations->where('status', \App\Enums\RegistrationStatus::CHECKED_IN)->count() }}
                    </div>
                    <div class="stat-lbl">عدد الحاضرين / Checked-In</div>
                </div>
            </td>
            <td width="25%">
                <div class="stat-card">
                    <div class="stat-val">{{ $event->registrations->count() }}</div>
                    <div class="stat-lbl">عدد المسجلين / Registered</div>
                </div>
            </td>
            <td width="25%">
                <div class="stat-card">
                    <div class="stat-val">{{ $event->capacity }}</div>
                    <div class="stat-lbl">السعة الاستيعابية / Capacity</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- List Section Header -->
    <div class="section-title">قائمة حضور المشاركين / Event Attendance List</div>

    <!-- Data Table (Visually RTL in LTR mode) -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="12%" style="text-align: center;">وقت الحضور / Checked-In At</th>
                <th width="12%" style="text-align: center;">حالة الحضور / Status</th>
                <th width="10%" style="text-align: center;">التخصص / Track</th>
                <th width="20%">المدرسة / School</th>
                <th width="23%">البريد الإلكتروني / Email</th>
                <th width="18%" style="text-align: right;">اسم المشارك / Participant Name</th>
                <th width="5%" style="text-align: center;">#</th>
            </tr>
        </thead>
        <tbody>
            @php $index = 1; @endphp
            @forelse($event->registrations as $reg)
                <tr>
                    <td style="text-align: center;">
                        {{ $reg->attendance ? \Carbon\Carbon::parse($reg->attendance->scan_time)->format('H:i:s') : '-' }}
                    </td>
                    <td style="text-align: center;">
                        @if($reg->status === \App\Enums\RegistrationStatus::CHECKED_IN)
                            <span class="badge badge-success">حاضر / Present</span>
                        @else
                            <span class="badge badge-danger">غائب / Absent</span>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $reg->user->specialization ?? '-' }}</td>
                    <td>{{ $reg->user->school_name ?? '-' }}</td>
                    <td>{{ $reg->user->email }}</td>
                    <td style="font-weight: bold; text-align: right;">{{ $reg->user->name }}</td>
                    <td style="text-align: center;">{{ $index++ }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #9ca3af; padding: 20px;">
                        لا يوجد مسجلين في هذه الفعالية حتى الآن.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer Section -->
    <div class="footer">
        <div class="slogan">حسن اختيارك هو بداية مشوارك | خلي ديما عندك وجهة</div>
        <div class="hashtag">#وجهتك_تبدأ_من_هنا</div>
        <div style="margin-top: 5px;">تم توليد هذا التقرير تلقائياً بواسطة منصة وجهة الرقمية 2026</div>
    </div>

</body>
</html>
