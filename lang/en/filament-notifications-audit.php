<?php

return [
    'notifications' => [
        'navigation' => [
            'label' => 'Notifications',
        ],
        'model_label' => 'Notification',
        'plural_model_label' => 'Notifications',
        'fields' => [
            'title_ar' => 'Title (Arabic)',
            'title_en' => 'Title (English)',
            'content_ar' => 'Content (Arabic)',
            'content_en' => 'Content (English)',
            'recipient_type' => 'Recipient Group',
            'user_id' => 'Target User',
            'role_id' => 'Target Role',
            'event_id' => 'Target Event',
            'scheduled_at' => 'Scheduled At',
            'scheduled_at_placeholder' => 'Leave empty to send immediately',
            'status' => 'Status',
        ],
        'table' => [
            'title' => 'Title',
            'recipient_type' => 'Recipient Group',
            'status' => 'Status',
            'scheduled_at' => 'Scheduled At',
            'delivered_at' => 'Sent At',
        ],
        'recipient_type' => [
            'all' => 'All Active Users',
            'individual' => 'Specific User',
            'role' => 'Specific Role Group',
            'event' => 'Event Attendees',
        ],
        'status' => [
            'draft' => 'Draft',
            'scheduled' => 'Scheduled',
            'processing' => 'Processing',
            'sent' => 'Sent',
            'failed' => 'Failed',
        ],
        'actions' => [
            'send_now' => 'Send Now',
        ],
    ],

    'audit_log' => [
        'navigation' => [
            'label' => 'Audit Logs',
        ],
        'model_label' => 'Audit Log',
        'plural_model_label' => 'Audit Logs',
        'fields' => [
            'user_name' => 'User Name',
            'action' => 'Action',
            'entity' => 'Entity',
            'entity_id' => 'Entity ID',
            'ip_address' => 'IP Address',
            'user_agent' => 'User Agent',
            'old_values' => 'Old Values',
            'new_values' => 'New Values',
            'created_at' => 'Timestamp',
        ],
        'table' => [
            'user' => 'User',
            'action' => 'Action',
            'entity' => 'Entity',
            'ip_address' => 'IP Address',
            'created_at' => 'Logged At',
        ],
        'action_type' => [
            'create' => 'Create',
            'update' => 'Update',
            'delete' => 'Delete',
        ],
    ],
];
