<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'منصة وجهة')</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F1F2F2;
            color: #333333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #FFFFFF;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .header {
            background-color: #001F8F;
            padding: 30px 20px;
            text-align: center;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            padding: 30px;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #777777;
            border-top: 1px solid #eeeeee;
        }
        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #FF4900;
            color: #FFFFFF !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }
        h1, h2, h3 {
            color: #001F8F;
        }
        .accent-text {
            color: #FF4900;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <!-- Use a placeholder or a reliable remote image if local asset is not available in emails -->
            <img src="{{ asset('assets/logo/wejha-logo.png') }}" alt="منصة وجهة" style="color:#ffffff; font-size:24px; font-weight:bold;">
        </div>
        <div class="content">
            @yield('content')
        </div>
        <div class="footer">
            <p>حسن اختيارك هو بداية مشوارك</p>
            <p>&copy; {{ date('Y') }} منصة وجهة. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>
