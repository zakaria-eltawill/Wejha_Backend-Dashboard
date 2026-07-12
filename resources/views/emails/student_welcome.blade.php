@extends('emails.layouts.wejha')

@section('title', 'مرحباً بك في وجهة')

@section('content')
    <h2>مرحباً {{ $user->name }}،</h2>
    <p>يسعدنا انضمامك إلى <span class="accent-text">منصة وجهة</span>!</p>

    <div class="info-box">
        <p style="margin: 0;">نحن هنا لمساعدتك في اتخاذ أفضل القرارات لمستقبلك الأكاديمي والمهني. من خلال المنصة، يمكنك استكشاف الفعاليات، حضور ورش العمل، والاستفادة من خدمات الإرشاد.</p>
    </div>

    <p style="text-align: center; font-weight: bold; color: #001F8F;">وجهتك تبدأ من هنا، نتمنى لك رحلة موفقة!</p>

    <div style="text-align: center;">
        <a href="{{ config('app.url') }}" class="button">اكتشف الفعاليات الآن</a>
    </div>
@endsection
