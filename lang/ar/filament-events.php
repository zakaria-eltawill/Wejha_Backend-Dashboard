<?php

return [
    'navigation' => [
        'label' => 'إدارة الفعاليات',
    ],

    'model_label' => 'فعالية',
    'plural_model_label' => 'فعاليات',

    'steps' => [
        'general_info' => 'معلومات الفعالية',
        'logistics' => 'التفاصيل اللوجستية',
        'registration' => 'التسجيل والحضور',
        'surveys' => 'الاستبيانات',
    ],

    'fields' => [
        'title_ar' => 'العنوان (بالعربية)',
        'title_en' => 'العنوان (بالإنجليزية)',
        'description_ar' => 'الوصف (بالعربية)',
        'description_en' => 'الوصف (بالإنجليزية)',
        'type' => 'نوع الفعالية',
        'speaker' => 'المتحدث',
        'status' => 'الحالة',
        'visibility' => 'الظهور',
        'featured' => 'مميز (تثبيت في البداية)',
        'banner_image' => 'صورة البانر',
        'cover_image' => 'صورة الغلاف',
        'event_date' => 'تاريخ بداية الفعالية',
        'event_time' => 'وقت بداية الفعالية',
        'end_date' => 'تاريخ نهاية الفعالية',
        'end_time' => 'وقت نهاية الفعالية',
        'venue' => 'الموقع',
        'venue_map_url' => 'رابط خريطة الموقع',
        'recording_url' => 'رابط الفيديو المسجل',
        'capacity' => 'السعة الاستيعابية',
        'registration_opens_at' => 'تاريخ فتح التسجيل',
        'registration_closes_at' => 'تاريخ إغلاق التسجيل',
        'qr_attendance_enabled' => 'تفعيل تحضير QR',
        'requires_approval' => 'يتطلب موافقة للتسجيل',
        'contact_person' => 'مسؤول التواصل',
        'organizer_notes' => 'ملاحظات المنظمين',
        'pre_survey_template_id' => 'اختر الاستبيان القبلي',
        'post_survey_template_id' => 'اختر الاستبيان البعدي',
        'survey_template' => 'نموذج الاستبيان',
        'evaluation_type' => 'نوع الاستبيان',
        'is_active' => 'نشط',
        'student_name' => 'اسم الطالب',
        'template' => 'نموذج الاستبيان',
        'question' => 'السؤال',
        'answer' => 'الإجابة',
        'submitted_at' => 'تاريخ الإرسال',
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'phone' => 'رقم الجوال',
        'school' => 'المدرسة',
        'specialization' => 'التخصص',
        'academic_year' => 'السنة الدراسية',
        'gender' => 'الجنس',
    ],

    'helper_texts' => [
        'pre_survey' => 'يظهر هذا الاستبيان للطالب عند التسجيل في الفعالية. اتركه فارغًا إن لم ترغب باستبيان قبلي.',
        'post_survey' => 'يظهر هذا الاستبيان للطالب بعد تسجيل حضوره في الفعالية. اتركه فارغًا إن لم ترغب باستبيان بعدي.',
        'end_date' => 'اتركه فارغًا إن كانت الفعالية ليوم واحد فقط. حدده لفعالية تمتد عدة أيام.',
        'end_time' => 'الوقت الذي تنتهي فيه الفعالية فعليًا. يُستخدم لتحديد متى تُعتبر الفعالية منتهية ويُمنع التسجيل الجديد فيها.',
        'recording_url' => 'رابط فيديو خارجي (يوتيوب / Google Drive / Vimeo) يظهر للطلاب بعد انتهاء الفعالية فقط، لمن لم يستطع الحضور.',
    ],

    'type' => [
        'seminar' => 'ندوة',
        'workshop' => 'ورشة عمل',
        'exhibition' => 'معرض',
    ],

    'event_status' => [
        'draft' => 'مسودة',
        'published' => 'منشور',
        'archived' => 'مؤرشف',
    ],

    'visibility' => [
        'public' => 'عام',
        'private' => 'خاص',
    ],

    'event_state' => [
        'ended' => 'منتهية',
        'upcoming' => 'قادمة',
    ],

    'table' => [
        'columns' => [
            'title_ar' => 'عنوان الفعالية بالعربية',
            'type' => 'النوع',
            'event_date' => 'التاريخ',
            'has_ended' => 'الحالة الزمنية',
            'capacity' => 'السعة',
            'featured' => 'مثبتة',
            'activity_ar' => 'النشاط (عربي)',
            'activity_en' => 'النشاط (إنجليزي)',
            'occurred_at' => 'التاريخ والوقت',
            'participant' => 'اسم المشارك',
            'survey' => 'الاستبيان',
            'participant_name' => 'اسم المشارك',
            'specialization' => 'التخصص',
            'source' => 'المصدر',
            'checkin_time' => 'وقت التحضير',
        ],
    ],

    'actions' => [
        'scan' => 'مسح',
        'scan_qr' => 'مسح التذاكر',
        'link_survey' => 'ربط استبيان جديد',
        'unlink' => 'إلغاء الربط',
        'view_details' => 'عرض التفاصيل',
        'approve' => 'قبول',
        'reject' => 'رفض',
        'checkin' => 'تحضير',
    ],

    'relation_managers' => [
        'activities' => [
            'title' => 'سجل الأنشطة والخط الزمني',
        ],
        'evaluations' => [
            'title' => 'التقييمات والاستبيانات المربوطة',
        ],
        'survey_responses' => [
            'title' => 'إجابات الطلاب على الاستبيانات',
        ],
        'registrations' => [
            'title' => 'التسجيلات والحضور',
        ],
    ],

    'pages' => [
        'scan' => [
            'heading_prefix' => 'ماسح التذاكر الفوري: ',
        ],
        'scan_page' => [
            'active_scanner' => 'كاميرا مسح التذاكر',
            'ready_for_event' => 'التحضير للفعالية المحددة',
            'select_camera' => 'اختر الكاميرا المفضلة',
            'loading_cameras' => 'جاري تحميل الكاميرات...',
            'start' => 'تشغيل الكاميرا',
            'stop' => 'إيقاف الكاميرا',
            'manual_entry_divider' => 'أو التحقق اليدوي',
            'manual_input_placeholder' => 'أدخل رمز التذكرة اليدوي...',
            'verify' => 'تحقق',
            'validation_details' => 'حالة البطاقة والمسح',
            'idle_title' => 'في انتظار البطاقة...',
            'idle_message' => 'يرجى تقريب رمز الـ QR من عدسة الكاميرا أو إدخال كود التذكرة يدوياً',
            'check_in_ok' => 'تم تأكيد حضور الطالب',
            'school_label' => 'المدرسة',
            'time_label' => 'وقت التحضير',
            'back_to_event' => 'العودة لصفحة الفعالية',
            'no_camera_found' => 'لم يتم العثور على كاميرات',
            'camera_access_blocked' => 'فشل الوصول للكاميرا',
            'scanning' => 'جاري الكشف...',
            'scanning_hint' => 'ضع الرمز في منتصف إطار الكاميرا للتحقق التلقائي',
            'standby' => 'في انتظار البدء...',
            'standby_hint' => 'انقر على تشغيل الكاميرا لبدء فحص رموز التذاكر',
            'validating' => 'جاري التحقق...',
            'scan_failed' => 'فشل المسح',
            'connection_error' => 'خطأ اتصال',
            'connection_error_hint' => 'فشل التحقق بسبب مشكلة في الاتصال بالخادم.',
            'device_label' => 'ماسح الفعالية',
        ],
    ],

    'evaluation_type_options' => [
        'pre' => 'استبيان قبلي (عند التسجيل)',
        'post' => 'استبيان بعدي (بعد الحضور)',
    ],

    'evaluation_type_badge' => [
        'pre' => 'استبيان قبلي',
        'post' => 'استبيان بعدي',
    ],

    'evaluation_badge_short' => [
        'pre' => 'قبلي',
        'post' => 'بعدي',
    ],

    'filter_evaluation_type' => [
        'pre' => 'قبلي',
        'post' => 'بعدي',
    ],

    'specialization' => [
        'scientific' => 'علمي',
        'literary' => 'أدبي',
    ],

    'gender' => [
        'male' => 'ذكر',
        'female' => 'أنثى',
    ],

    'registration_status' => [
        'pending' => 'قيد الانتظار',
        'approved' => 'مقبول',
        'rejected' => 'مرفوض',
        'cancelled' => 'ملغي',
        'checked_in' => 'تم تسجيل الحضور',
    ],

    'source' => [
        'web' => 'الموقع الإلكتروني',
        'mobile' => 'تطبيق الجوال',
        'admin' => 'لوحة التحكم',
    ],
];
