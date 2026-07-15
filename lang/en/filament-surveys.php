<?php

return [
    'navigation' => [
        'label' => 'Surveys',
    ],

    'model_label' => 'Survey Template',
    'plural_model_label' => 'Survey Templates',

    'fields' => [
        'name_ar' => 'Name (Arabic)',
        'name_en' => 'Name (English)',
        'version' => 'Version',
        'status' => 'Status',
        'category' => 'Category',
        'type' => 'Survey Type',
        'is_reusable' => 'Reusable',
        'description_ar' => 'Description (Arabic)',
        'description_en' => 'Description (English)',
    ],

    'status' => [
        'draft' => 'Draft',
        'active' => 'Active',
        'archived' => 'Archived',
    ],

    'type' => [
        'pre' => 'Pre-Assessment',
        'post' => 'Post-Assessment',
    ],

    'sections' => [
        'questions_heading' => 'Survey Questions',
        'questions_description' => 'Add your questions one at a time. Drag to reorder.',
        'additional_details' => 'Additional details (optional)',
    ],

    'question_fields' => [
        'type' => 'Question Type',
        'type_helper' => 'Choose how the student will answer.',
        'question_text_ar' => 'Question (Arabic)',
        'question_text_en' => 'Question (English)',
        'options' => 'Answer Options',
        'options_helper' => 'Add each choice on its own line, in the order students will see them.',
        'option_value' => 'Option',
        'is_required' => 'Required question?',
        'is_required_helper' => 'Student cannot submit the survey without answering this.',
        'description_ar' => 'Description (Arabic)',
        'description_en' => 'Description (English)',
        'help_text_ar' => 'Help text (Arabic)',
        'help_text_en' => 'Help text (English)',
        'score' => 'Score',
        'new_question_label' => 'New question',
    ],

    'actions' => [
        'add_option' => 'Add option',
        'add_question' => 'Add new question',
        'delete_question_heading' => 'Delete this question?',
        'delete_question_description' => 'This permanently deletes the question and any student answers to it. This cannot be undone.',
        'delete_question_confirm' => 'Yes, delete',
        'preview' => 'Preview',
        'preview_survey' => 'Preview',
        'clone' => 'Clone',
        'import' => 'Import JSON',
    ],

    'table' => [
        'name' => 'Survey Name',
        'type' => 'Type',
        'type_pre' => 'Pre-Assessment',
        'type_post' => 'Post-Assessment',
    ],

    'preview' => [
        'title' => 'Survey Preview',
        'subheading' => 'This is how the survey will look to a student. Preview only — answers cannot be submitted here.',
        'no_questions' => 'No questions yet. Add questions from the edit page to see them here.',
        'answer_placeholder' => 'Student answer here',
        'no_options' => '⚠ No options added yet',
    ],
];
