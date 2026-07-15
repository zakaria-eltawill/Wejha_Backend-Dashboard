<?php

return [
    'notifications' => [
        'navigation' => [
            'label' => 'الإشعارات والاتصالات',
        ],
        'model_label' => 'إشعار',
        'plural_model_label' => 'إشعارات',
        'fields' => [
            'title_ar' => 'العنوان (بالعربية)',
            'title_en' => 'العنوان (بالإنجليزية)',
            'content_ar' => 'المحتوى (بالعربية)',
            'content_en' => 'المحتوى (بالإنجليزية)',
            'recipient_type' => 'نوع المستلمين',
            'user_id' => 'المستخدم المستهدف',
            'role_id' => 'المجموعة المستهدفة',
            'event_id' => 'الفعالية المستهدفة',
            'scheduled_at' => 'وقت الجدولة',
            'scheduled_at_placeholder' => 'اتركه فارغاً للإرسال الفوري',
            'status' => 'الحالة',
        ],
        'table' => [
            'title' => 'عنوان الإشعار',
            'recipient_type' => 'نوع المستلمين',
            'status' => 'الحالة',
            'scheduled_at' => 'مجدول في',
            'delivered_at' => 'تم الإرسال في',
        ],
        'recipient_type' => [
            'all' => 'الكل',
            'individual' => 'مستخدم محدد',
            'role' => 'مجموعة صلاحية',
            'event' => 'المسجلون في فعالية',
        ],
        'status' => [
            'draft' => 'مسودة',
            'scheduled' => 'مجدول',
            'processing' => 'جارِ الإرسال',
            'sent' => 'تم الإرسال',
            'failed' => 'فشل',
        ],
        'actions' => [
            'send_now' => 'إرسال الآن',
        ],
    ],

    'audit_log' => [
        'navigation' => [
            'label' => 'سجلات المراجعة والأمان',
        ],
        'model_label' => 'سجل مراجعة',
        'plural_model_label' => 'سجلات مراجعة',
        'fields' => [
            'user_name' => 'اسم المستخدم',
            'action' => 'العملية',
            'entity' => 'الكيان',
            'entity_id' => 'معرّف الكيان',
            'ip_address' => 'عنوان IP',
            'user_agent' => 'متصفح المستخدم',
            'old_values' => 'القيم السابقة',
            'new_values' => 'القيم الجديدة',
            'created_at' => 'تاريخ العملية',
        ],
        'table' => [
            'user' => 'المستخدم',
            'action' => 'العملية',
            'entity' => 'الجدول',
            'ip_address' => 'عنوان IP',
            'created_at' => 'الوقت',
        ],
        'action_type' => [
            'create' => 'إنشاء',
            'update' => 'تحديث',
            'delete' => 'حذف',
        ],
    ],
];
