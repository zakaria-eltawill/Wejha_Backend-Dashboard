<?php

return [
    'navigation' => [
        'label' => 'إدارة الاستبيانات',
    ],

    'model_label' => 'استبيان',
    'plural_model_label' => 'استبيانات',

    'fields' => [
        'name_ar' => 'اسم النموذج بالعربية',
        'name_en' => 'اسم النموذج بالإنجليزية',
        'version' => 'الإصدار',
        'status' => 'الحالة',
        'category' => 'التصنيف',
        'type' => 'نوع الاستبيان',
        'is_reusable' => 'قابل لإعادة الاستخدام',
        'description_ar' => 'الوصف بالعربية',
        'description_en' => 'الوصف بالإنجليزية',
    ],

    'status' => [
        'draft' => 'مسودة',
        'active' => 'نشط',
        'archived' => 'مؤرشف',
    ],

    'type' => [
        'pre' => 'استبيان قبلي (عند التسجيل)',
        'post' => 'استبيان بعدي (بعد الحضور)',
    ],

    'sections' => [
        'questions_heading' => 'أسئلة الاستبيان',
        'questions_description' => 'أضف أسئلتك واحدًا تلو الآخر. اسحب من المقبض 🟰 لإعادة الترتيب.',
    ],

    'question_fields' => [
        'type' => 'نوع السؤال',
        'type_helper' => 'اختر شكل الإجابة التي تريدها من الطالب.',
        'question_text_ar' => 'عنوان السؤال بالعربية',
        'question_text_en' => 'عنوان السؤال بالإنجليزية',
        'options' => 'خيارات الإجابة',
        'options_helper' => 'أضف كل خيار في سطر منفصل، بنفس الترتيب الذي سيراه الطالب.',
        'option_value' => 'الخيار',
        'is_required' => 'سؤال إجباري؟',
        'is_required_helper' => 'لن يتمكن الطالب من إرسال الاستبيان دون الإجابة على هذا السؤال.',
        'new_question_label' => 'سؤال جديد',
    ],

    'actions' => [
        'add_option' => '+ إضافة خيار',
        'add_question' => '+ إضافة سؤال جديد',
        'delete_question_heading' => 'حذف هذا السؤال؟',
        'delete_question_description' => 'سيتم حذف السؤال وكل إجابات الطلاب عليه نهائيًا، ولا يمكن التراجع عن هذا الإجراء.',
        'delete_question_confirm' => 'نعم، احذف',
        'preview' => 'معاينة',
        'preview_survey' => 'معاينة الاستبيان',
        'clone' => 'نسخ',
        'import' => 'استيراد نموذج',
    ],

    'table' => [
        'name' => 'اسم الاستبيان',
        'type' => 'نوع الاستبيان',
        'type_pre' => 'استبيان قبلي',
        'type_post' => 'استبيان بعدي',
    ],

    'preview' => [
        'title' => 'معاينة الاستبيان',
        'subheading' => 'هكذا سيظهر الاستبيان للطالب. هذه معاينة فقط ولا يمكن إرسال إجابات منها.',
        'no_questions' => 'لا توجد أسئلة بعد. أضف أسئلة من صفحة التعديل لتظهر هنا.',
        'answer_placeholder' => 'إجابة الطالب هنا',
        'no_options' => '⚠ لم تُضف أي خيارات بعد',
    ],
];
