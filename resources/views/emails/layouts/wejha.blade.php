@php
    $logoPath = public_path('assets/logo/wejha_logo_vertical_light_gradient_transparent.png');
    if (file_exists($logoPath) && isset($message) && method_exists($message, 'embed')) {
        // CID inline attachment: the only reliable way to show images in Gmail,
        // which strips "data:" base64 image URIs from HTML emails.
        $logoSrc = $message->embed($logoPath);
    } elseif (file_exists($logoPath)) {
        $logoSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    } else {
        $logoSrc = asset('assets/logo/wejha-logo.png');
    }
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'منصة وجهة')</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #EEF1F8;
            color: #333333;
            margin: 0;
            padding: 0;
            line-height: 1.7;
            direction: rtl;
            text-align: right;
        }
        .outer {
            width: 100%;
            padding: 40px 16px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 31, 143, 0.12);
        }
        .top-bar {
            height: 6px;
            background: linear-gradient(90deg, #001F8F 0%, #0033CC 50%, #FF4900 100%);
        }
        .header {
            background: linear-gradient(160deg, #001F8F 0%, #001560 100%);
            padding: 40px 20px 32px;
            text-align: center;
        }
        .header img {
            max-width: 130px;
            height: auto;
        }
        .content {
            padding: 36px 34px;
            direction: rtl;
            text-align: right;
        }
        .content p, .content h1, .content h2, .content h3, .content li {
            direction: rtl;
            text-align: right;
        }
        .content p {
            font-size: 15px;
            color: #444444;
        }
        .info-box {
            background-color: #F5F7FC;
            border-right: 4px solid #FF4900;
            padding: 18px 20px;
            border-radius: 8px;
            margin-bottom: 22px;
            direction: rtl;
            text-align: right;
        }
        .footer {
            background-color: #FAFAFC;
            padding: 26px 20px;
            text-align: center;
            font-size: 13px;
            color: #8A8F9C;
            border-top: 1px solid #EEEEEE;
        }
        .footer p {
            margin: 4px 0;
        }
        .footer .brand {
            color: #001F8F;
            font-weight: bold;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(90deg, #FF4900 0%, #FF6B2C 100%);
            color: #FFFFFF !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 15px;
            margin-top: 20px;
            box-shadow: 0 4px 12px rgba(255, 73, 0, 0.3);
        }
        h1, h2, h3 {
            color: #001F8F;
        }
        .accent-text {
            color: #FF4900;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="outer">
        <div class="container">
            <div class="top-bar"></div>
            <div class="header">
                <img src="{{ $logoSrc }}" alt="منصة وجهة">
            </div>
            <div class="content">
                @yield('content')
            </div>
            <div class="footer">
                <p class="brand">منصة وجهة</p>
                <p>حسن اختيارك هو بداية مشوارك</p>
                <p>&copy; {{ date('Y') }} منصة وجهة. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </div>
</body>
</html>
