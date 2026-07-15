<?php

return [
    'navigation' => [
        'label' => 'Events',
    ],

    'model_label' => 'Event',
    'plural_model_label' => 'Events',

    'steps' => [
        'general_info' => 'General Info',
        'logistics' => 'Logistics',
        'registration' => 'Registration',
        'surveys' => 'Surveys',
    ],

    'fields' => [
        'title_ar' => 'Title (Arabic)',
        'title_en' => 'Title (English)',
        'description_ar' => 'Description (Arabic)',
        'description_en' => 'Description (English)',
        'type' => 'Type',
        'speaker' => 'Speaker',
        'status' => 'Status',
        'visibility' => 'Visibility',
        'featured' => 'Featured',
        'banner_image' => 'Banner Image',
        'cover_image' => 'Cover Image',
        'event_date' => 'Start Date',
        'event_time' => 'Start Time',
        'end_date' => 'End Date',
        'end_time' => 'End Time',
        'venue' => 'Venue',
        'venue_map_url' => 'Map URL',
        'recording_url' => 'Recorded Video URL',
        'capacity' => 'Capacity',
        'registration_opens_at' => 'Registration Opens',
        'registration_closes_at' => 'Registration Closes',
        'qr_attendance_enabled' => 'QR Check-In',
        'requires_approval' => 'Requires Approval',
        'contact_person' => 'Contact Person',
        'organizer_notes' => 'Organizer Notes',
        'pre_survey_template_id' => 'Pre-Survey',
        'post_survey_template_id' => 'Post-Survey',
        'survey_template' => 'Survey Template',
        'evaluation_type' => 'Survey Type',
        'is_active' => 'Active',
        'student_name' => 'Student Name',
        'template' => 'Template',
        'question' => 'Question',
        'answer' => 'Answer',
        'submitted_at' => 'Submitted At',
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'school' => 'School',
        'specialization' => 'Specialization',
        'academic_year' => 'Year',
        'gender' => 'Gender',
    ],

    'helper_texts' => [
        'pre_survey' => 'Shown to the student when they register. Leave empty for none.',
        'post_survey' => 'Shown to the student after they check in. Leave empty for none.',
        'end_date' => 'Leave empty for a single-day event. Set it for an event spanning multiple days.',
        'end_time' => 'The time the event actually ends. Used to determine when the event is considered over and new registrations are blocked.',
        'recording_url' => 'An external video link (YouTube / Google Drive / Vimeo) shown to students only after the event has ended, for those who could not attend.',
    ],

    'type' => [
        'seminar' => 'Seminar',
        'workshop' => 'Workshop',
        'exhibition' => 'Exhibition',
    ],

    'event_status' => [
        'draft' => 'Draft',
        'published' => 'Published',
        'archived' => 'Archived',
    ],

    'visibility' => [
        'public' => 'Public',
        'private' => 'Private',
    ],

    'event_state' => [
        'ended' => 'Ended',
        'upcoming' => 'Upcoming',
    ],

    'table' => [
        'columns' => [
            'title_ar' => 'Title (Arabic)',
            'type' => 'Type',
            'event_date' => 'Date',
            'has_ended' => 'Timing',
            'capacity' => 'Capacity',
            'featured' => 'Featured',
            'activity_ar' => 'Activity (Arabic)',
            'activity_en' => 'Activity (English)',
            'occurred_at' => 'Time',
            'participant' => 'Participant',
            'survey' => 'Survey',
            'participant_name' => 'Name',
            'specialization' => 'Track',
            'source' => 'Source',
            'checkin_time' => 'Check-In Time',
        ],
    ],

    'actions' => [
        'scan' => 'Scan',
        'scan_qr' => 'Scan QR',
        'link_survey' => 'Link Survey',
        'unlink' => 'Unlink',
        'view_details' => 'View',
        'approve' => 'Approve',
        'reject' => 'Reject',
        'checkin' => 'Check In',
    ],

    'relation_managers' => [
        'activities' => [
            'title' => 'Timeline & Activity Log',
        ],
        'evaluations' => [
            'title' => 'Linked Surveys',
        ],
        'survey_responses' => [
            'title' => 'Student Survey Answers',
        ],
        'registrations' => [
            'title' => 'Registrations & Attendance',
        ],
    ],

    'pages' => [
        'scan' => [
            'heading_prefix' => 'Live Ticket Scanner: ',
        ],
        'scan_page' => [
            'active_scanner' => 'Active Scanner',
            'ready_for_event' => 'Preparing for the selected event',
            'select_camera' => 'Select Camera Source',
            'loading_cameras' => 'Loading cameras...',
            'start' => 'Start',
            'stop' => 'Stop',
            'manual_entry_divider' => 'Manual Entry',
            'manual_input_placeholder' => 'Enter ticket code manually...',
            'verify' => 'Verify',
            'validation_details' => 'Validation Details',
            'idle_title' => 'Waiting for ticket...',
            'idle_message' => 'Please bring the QR code close to the camera lens or enter the ticket code manually',
            'check_in_ok' => 'Check-in OK',
            'school_label' => 'School',
            'time_label' => 'Time',
            'back_to_event' => 'Back to Event',
            'no_camera_found' => 'No camera found',
            'camera_access_blocked' => 'Camera access blocked',
            'scanning' => 'Scanning...',
            'scanning_hint' => 'Place the code in the middle of the camera frame for automatic verification',
            'standby' => 'Standby',
            'standby_hint' => 'Click Start Camera to begin scanning tickets',
            'validating' => 'Validating...',
            'scan_failed' => 'Scan Failed',
            'connection_error' => 'Connection Error',
            'connection_error_hint' => 'Verification failed due to a server connection issue.',
            'device_label' => 'Event Scanner',
        ],
    ],

    'evaluation_type_options' => [
        'pre' => 'Pre-Assessment',
        'post' => 'Post-Assessment',
    ],

    'evaluation_type_badge' => [
        'pre' => 'Pre-Assessment',
        'post' => 'Post-Assessment',
    ],

    'evaluation_badge_short' => [
        'pre' => 'Pre',
        'post' => 'Post',
    ],

    'filter_evaluation_type' => [
        'pre' => 'Pre-Assessment',
        'post' => 'Post-Assessment',
    ],

    'specialization' => [
        'scientific' => 'Scientific',
        'literary' => 'Literary',
    ],

    'gender' => [
        'male' => 'Male',
        'female' => 'Female',
    ],

    'registration_status' => [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'cancelled' => 'Cancelled',
        'checked_in' => 'Checked In',
    ],

    'source' => [
        'web' => 'Web',
        'mobile' => 'Mobile',
        'admin' => 'Admin Panel',
    ],
];
