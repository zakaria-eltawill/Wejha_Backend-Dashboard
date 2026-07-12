@extends('emails.layouts.wejha')

@section('title', 'تذكير بالفعالية')

@section('content')
    <h2>أهلاً {{ $user->name }}،</h2>
    <p>نود تذكيرك بأن الفعالية التي سجلت بها ستنطلق <span class="accent-text">غداً</span>!</p>
    
    <div style="background-color: #f5f7fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <h3 style="margin-top: 0;">تفاصيل الفعالية:</h3>
        <p><strong>الاسم:</strong> {{ $event->title_ar }}</p>
        <p><strong>التاريخ:</strong> {{ $event->event_date?->format('Y-m-d') }}</p>
        <p><strong>الوقت:</strong> {{ $event->event_time }}</p>
        <p><strong>المكان:</strong> {{ $event->venue }}</p>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <p style="margin-bottom: 10px; font-weight: bold;">تذكرة الدخول (QR Code)</p>
        <p style="font-size: 13px; color: #666;">لا تنسَ إحضار التذكرة لتسهيل عملية دخولك.</p>
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($registration->qr_hash) }}" alt="QR Ticket" style="border: 2px solid #001F8F; border-radius: 10px; padding: 10px;">
    </div>
    
    <p style="text-align: center;">بانتظارك غداً!</p>
@endsection
