<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventEvaluation;
use App\Models\Notification;
use App\Models\Registration;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\SurveyTemplate;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Temporary load/demo-data seeder. Every record it creates is tagged so it can be
 * removed precisely later without touching real data:
 *   - Users:  email like "demo###@wejha-demo.test"
 *   - Events: title_ar/title_en prefixed "[DEMO] ", organizer_notes = "DEMO_SEED_DATA"
 *   - Survey templates: name_ar/name_en prefixed "[DEMO] "
 *   - Notifications: title_ar prefixed "[DEMO] "
 * Registrations/attendance/survey_responses are only ever created against the demo
 * users/events above, so cascading deletes on those two anchors clean up everything.
 *
 * Run: php artisan db:seed --class=DemoDataSeeder
 */
class DemoDataSeeder extends Seeder
{
    private const DEMO_EMAIL_DOMAIN = 'wejha-demo.test';
    private const DEMO_TITLE_PREFIX = '[DEMO] ';

    private array $schools = [
        'ثانوية طرابلس المركزية', 'ثانوية بنغازي النموذجية', 'ثانوية مصراتة',
        'ثانوية الأمير فيصل', 'ثانوية الزاوية', 'ثانوية سبها', 'ثانوية درنة',
        'ثانوية الخمس', 'ثانوية زليتن', 'ثانوية أجدابيا', 'ثانوية توكرة',
        'ثانوية غريان', 'ثانوية البيضاء', 'ثانوية سرت', 'ثانوية المرج',
    ];

    private array $firstNamesM = ['أحمد', 'محمد', 'علي', 'عمر', 'يوسف', 'خالد', 'إبراهيم', 'حسين', 'زياد', 'كريم', 'سالم', 'فيصل', 'طارق', 'وليد', 'أنس'];
    private array $firstNamesF = ['فاطمة', 'مريم', 'عائشة', 'خديجة', 'زينب', 'سارة', 'نور', 'هدى', 'أمل', 'ليلى', 'رحاب', 'إيمان', 'سلمى', 'ياسمين', 'رنا'];
    private array $lastNames = ['التويجري', 'المصراتي', 'الزليتني', 'البرعصي', 'الورفلي', 'المسماري', 'الفيتوري', 'القذافي', 'الطرابلسي', 'السنوسي', 'المغربي', 'الشريف', 'العريبي', 'الفزاني', 'المجبري'];

    private array $eventTitlesAr = [
        'ملتقى القيادة الطلابية', 'ورشة مهارات البرمجة', 'معرض المشاريع التقنية',
        'ندوة التخطيط المهني', 'ورشة الذكاء الاصطناعي', 'ملتقى ريادة الأعمال',
        'ندوة التعليم الجامعي', 'ورشة التصميم الجرافيكي', 'معرض الابتكار الطلابي',
        'ملتقى المنح الدراسية', 'ورشة العمل الجماعي', 'ندوة الصحة النفسية',
        'ورشة الكتابة الإبداعية', 'معرض التوظيف السنوي', 'ملتقى قادة المستقبل',
        'ندوة اللغة الإنجليزية', 'ورشة إدارة الوقت', 'معرض الفنون الطلابية',
        'ملتقى التطوع المجتمعي', 'ندوة الأمن السيبراني', 'ورشة تحليل البيانات',
        'معرض الجامعات', 'ملتقى الابتكار العلمي', 'ندوة مهارات المقابلات',
    ];
    private array $eventTitlesEn = [
        'Student Leadership Forum', 'Programming Skills Workshop', 'Tech Projects Exhibition',
        'Career Planning Seminar', 'AI Workshop', 'Entrepreneurship Forum',
        'University Education Seminar', 'Graphic Design Workshop', 'Student Innovation Expo',
        'Scholarships Forum', 'Teamwork Workshop', 'Mental Health Seminar',
        'Creative Writing Workshop', 'Annual Career Fair', 'Future Leaders Forum',
        'English Language Seminar', 'Time Management Workshop', 'Student Arts Exhibition',
        'Community Volunteering Forum', 'Cybersecurity Seminar', 'Data Analysis Workshop',
        'Universities Fair', 'Scientific Innovation Forum', 'Interview Skills Seminar',
    ];

    public function run(): void
    {
        $this->command?->info('Seeding demo data...');

        $admin = User::where('email', 'admin@wejha.com')->first();
        if (!$admin) {
            $this->command?->error('Admin user not found — run the main DatabaseSeeder first.');
            return;
        }

        $students = $this->seedStudents(200);
        $this->command?->info('Created ' . count($students) . ' demo students.');

        $events = $this->seedEvents($admin->id, 24);
        $this->command?->info('Created ' . count($events) . ' demo events.');

        $templates = $this->seedSurveyTemplates();
        $this->command?->info('Created ' . count($templates) . ' demo survey templates.');

        $this->linkEvaluations($events, $templates);

        $this->seedRegistrationsAndAttendance($students, $events);
        $this->command?->info('Seeded registrations + attendance.');

        $this->seedSurveyResponses($students, $events);
        $this->command?->info('Seeded survey responses.');

        $this->seedNotifications($admin->id, $events, count($students));
        $this->command?->info('Seeded notifications.');

        $this->command?->info('Demo data seeding complete.');
    }

    private function seedStudents(int $count): array
    {
        $ids = [];
        $rows = [];
        $now = now();
        $specializations = ['scientific', 'literary'];

        for ($i = 1; $i <= $count; $i++) {
            $isMale = fake()->boolean();
            $first = $isMale ? fake()->randomElement($this->firstNamesM) : fake()->randomElement($this->firstNamesF);
            $last = fake()->randomElement($this->lastNames);
            $id = (string) Str::uuid();
            $ids[] = $id;

            $rows[] = [
                'id' => $id,
                'name' => "{$first} {$last}",
                'username' => null,
                'email' => "demo{$i}@" . self::DEMO_EMAIL_DOMAIN,
                'password' => Hash::make('password'),
                'api_token' => Str::random(60),
                'phone_number' => '09' . fake()->numerify('########'),
                'gender' => $isMale ? 'male' : 'female',
                'academic_year' => fake()->randomElement(['السنة الأولى', 'السنة الثانية', 'السنة الثالثة', 'السنة الرابعة']),
                'school_name' => fake()->randomElement($this->schools),
                'specialization' => fake()->randomElement($specializations),
                'preferred_language' => 'ar',
                'preferred_theme' => 'system',
                'timezone' => 'Africa/Tripoli',
                'notification_preferences' => null,
                'avatar' => null,
                'status' => 'active',
                'last_login_at' => fake()->boolean(70) ? fake()->dateTimeBetween('-60 days', 'now') : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('users')->insert($chunk);
        }

        // Assign Student role via Spatie pivot directly (bulk) for speed.
        $studentRoleId = DB::table('roles')->where('name', 'Student')->value('id');
        if ($studentRoleId) {
            $pivotRows = array_map(fn ($id) => [
                'role_id' => $studentRoleId,
                'model_type' => User::class,
                'model_id' => $id,
            ], $ids);
            foreach (array_chunk($pivotRows, 200) as $chunk) {
                DB::table('model_has_roles')->insert($chunk);
            }
        }

        return $ids;
    }

    private function seedEvents(string $creatorId, int $count): array
    {
        $ids = [];
        $rows = [];
        $now = now();
        $types = ['seminar', 'workshop', 'exhibition'];
        $venues = ['قاعة بنغازي الكبرى', 'مركز طرابلس للمؤتمرات', 'جامعة مصراتة - المدرج الرئيسي', 'فندق كورينثيا', 'مركز الشباب الرقمي'];

        for ($i = 0; $i < $count; $i++) {
            // Spread events: ~40% past (archived-eligible), ~15% ongoing multi-day, ~45% upcoming.
            $bucket = $i % 20;
            if ($bucket < 8) {
                $eventDate = fake()->dateTimeBetween('-90 days', '-2 days');
                $status = 'archived';
                $endDate = null;
            } elseif ($bucket < 11) {
                $eventDate = (clone $now)->modify('-1 day');
                $endDate = (clone $now)->modify('+2 days');
                $status = 'published';
            } else {
                $eventDate = fake()->dateTimeBetween('+3 days', '+90 days');
                $status = 'published';
                $endDate = fake()->boolean(20) ? (clone $eventDate)->modify('+' . rand(1, 3) . ' days') : null;
            }

            $titleIdx = $i % count($this->eventTitlesAr);
            $suffix = intdiv($i, count($this->eventTitlesAr)) > 0 ? ' ' . (intdiv($i, count($this->eventTitlesAr)) + 1) : '';
            $capacity = fake()->randomElement([30, 50, 80, 100, 150, 200]);

            $id = (string) Str::uuid();
            $ids[] = ['id' => $id, 'status' => $status, 'event_date' => $eventDate, 'capacity' => $capacity];

            $rows[] = [
                'id' => $id,
                'title_ar' => self::DEMO_TITLE_PREFIX . $this->eventTitlesAr[$titleIdx] . $suffix,
                'title_en' => self::DEMO_TITLE_PREFIX . $this->eventTitlesEn[$titleIdx] . $suffix,
                'description_ar' => 'وصف تجريبي لفعالية: ' . $this->eventTitlesAr[$titleIdx] . '. هذه بيانات تجريبية لأغراض الاختبار.',
                'description_en' => 'Demo description for: ' . $this->eventTitlesEn[$titleIdx] . '. This is test seed data.',
                'type' => fake()->randomElement($types),
                'banner_image' => null,
                'cover_image' => null,
                'speaker' => fake()->name(),
                'event_date' => $eventDate->format('Y-m-d'),
                'event_time' => sprintf('%02d:00:00', rand(9, 17)),
                'end_date' => $endDate?->format('Y-m-d'),
                'end_time' => $endDate ? sprintf('%02d:00:00', rand(14, 20)) : null,
                'venue' => fake()->randomElement($venues),
                'venue_map_url' => null,
                'recording_url' => ($status === 'archived' && fake()->boolean(30)) ? 'https://youtube.com/watch?v=demo' . $i : null,
                'capacity' => $capacity,
                'registration_opens_at' => null,
                'registration_closes_at' => null,
                'qr_attendance_enabled' => true,
                'requires_approval' => fake()->boolean(15),
                'status' => $status,
                'visibility' => 'public',
                'featured' => fake()->boolean(10),
                'organizer_notes' => 'DEMO_SEED_DATA',
                'contact_person' => fake()->name(),
                'creator_id' => $creatorId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 50) as $chunk) {
            DB::table('events')->insert($chunk);
        }

        return $ids;
    }

    private function seedSurveyTemplates(): array
    {
        $now = now();
        $templates = [
            ['name_ar' => self::DEMO_TITLE_PREFIX . 'استبيان ما قبل الفعالية', 'name_en' => self::DEMO_TITLE_PREFIX . 'Pre-Event Survey', 'type' => 'pre'],
            ['name_ar' => self::DEMO_TITLE_PREFIX . 'استبيان تقييم الفعالية', 'name_en' => self::DEMO_TITLE_PREFIX . 'Post-Event Evaluation', 'type' => 'post'],
        ];

        $result = [];

        foreach ($templates as $t) {
            $templateId = (string) Str::uuid();
            DB::table('survey_templates')->insert([
                'id' => $templateId,
                'name_ar' => $t['name_ar'],
                'name_en' => $t['name_en'],
                'version' => '1.0',
                'status' => 'active',
                'category' => 'demo',
                'type' => $t['type'],
                'is_reusable' => true,
                'description_ar' => 'استبيان تجريبي لأغراض الاختبار.',
                'description_en' => 'Demo survey template for testing.',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $questions = $t['type'] === 'pre'
                ? [
                    ['type' => 'text', 'ar' => 'ما الذي تتوقعه من هذه الفعالية؟', 'en' => 'What do you expect from this event?', 'options' => null],
                    ['type' => 'multiple_choice', 'ar' => 'كيف علمت بهذه الفعالية؟', 'en' => 'How did you hear about this event?', 'options' => ['مواقع التواصل', 'صديق', 'المدرسة', 'أخرى']],
                    ['type' => 'rating', 'ar' => 'ما مدى حماسك لحضور هذه الفعالية؟', 'en' => 'How excited are you to attend?', 'options' => null],
                ]
                : [
                    ['type' => 'rating', 'ar' => 'ما تقييمك العام للفعالية؟', 'en' => 'Overall rating of the event?', 'options' => null],
                    ['type' => 'rating', 'ar' => 'ما تقييمك لتنظيم الفعالية؟', 'en' => 'Rating of the event organization?', 'options' => null],
                    ['type' => 'multiple_choice', 'ar' => 'هل استفدت من هذه الفعالية؟', 'en' => 'Did you benefit from this event?', 'options' => ['نعم كثيرًا', 'إلى حد ما', 'قليلاً', 'لا']],
                    ['type' => 'checkbox', 'ar' => 'ما الجوانب التي أعجبتك؟', 'en' => 'Which aspects did you like?', 'options' => ['المحتوى', 'المتحدث', 'التنظيم', 'المكان', 'التوقيت']],
                    ['type' => 'textarea', 'ar' => 'أي ملاحظات أو اقتراحات؟', 'en' => 'Any feedback or suggestions?', 'options' => null],
                ];

            $questionIds = [];
            $rows = [];
            foreach ($questions as $idx => $q) {
                $qId = (string) Str::uuid();
                $questionIds[] = ['id' => $qId, 'type' => $q['type'], 'options' => $q['options']];
                $rows[] = [
                    'id' => $qId,
                    'survey_template_id' => $templateId,
                    'type' => $q['type'],
                    'question_text_ar' => $q['ar'],
                    'question_text_en' => $q['en'],
                    'options' => $q['options'] ? json_encode($q['options'], JSON_UNESCAPED_UNICODE) : null,
                    'is_required' => true,
                    'sort_order' => $idx + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('survey_questions')->insert($rows);

            $result[] = ['id' => $templateId, 'type' => $t['type'], 'questions' => $questionIds];
        }

        return $result;
    }

    private function linkEvaluations(array $events, array $templates): void
    {
        $now = now();
        $preTemplate = collect($templates)->firstWhere('type', 'pre');
        $postTemplate = collect($templates)->firstWhere('type', 'post');
        $rows = [];

        foreach ($events as $event) {
            // Only link surveys to a subset of events (60%) for realistic variety.
            if (!fake()->boolean(60)) {
                continue;
            }
            $rows[] = [
                'id' => (string) Str::uuid(),
                'event_id' => $event['id'],
                'survey_template_id' => $preTemplate['id'],
                'evaluation_type' => 'pre',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $rows[] = [
                'id' => (string) Str::uuid(),
                'event_id' => $event['id'],
                'survey_template_id' => $postTemplate['id'],
                'evaluation_type' => 'post',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('event_evaluations')->insert($chunk);
        }
    }

    private function seedRegistrationsAndAttendance(array $students, array $events): void
    {
        $now = now();
        $regRows = [];
        $attRows = [];
        $registeredPairs = [];

        foreach ($events as $event) {
            $isPast = $event['status'] === 'archived';
            $fillRatio = fake()->randomFloat(2, 0.3, 0.9);
            $regCount = min((int) ($event['capacity'] * $fillRatio), count($students));
            $selectedStudents = (array) array_rand(array_flip($students), max(1, $regCount));
            if (!is_array($selectedStudents)) {
                $selectedStudents = [$selectedStudents];
            }

            foreach ($selectedStudents as $studentId) {
                $key = $studentId . '|' . $event['id'];
                if (isset($registeredPairs[$key])) {
                    continue;
                }
                $registeredPairs[$key] = true;

                $regId = (string) Str::uuid();
                $status = $isPast
                    ? fake()->randomElement(['checked_in', 'checked_in', 'checked_in', 'approved', 'approved', 'cancelled'])
                    : fake()->randomElement(['approved', 'approved', 'approved', 'pending']);

                $qrHash = Str::random(40);
                $regRows[] = [
                    'id' => $regId,
                    'user_id' => $studentId,
                    'event_id' => $event['id'],
                    'qr_hash' => $qrHash,
                    'source' => fake()->randomElement(['mobile', 'mobile', 'web']),
                    'status' => $status,
                    'registered_at' => fake()->dateTimeBetween('-95 days', $event['event_date']->format('Y-m-d') ?? 'now'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if ($status === 'checked_in') {
                    $attRows[] = [
                        'id' => (string) Str::uuid(),
                        'registration_id' => $regId,
                        'scanner_user_id' => null,
                        'scan_time' => fake()->dateTimeBetween($event['event_date'], (clone $event['event_date'])->modify('+8 hours')),
                        'device' => fake()->randomElement(['Web Camera', 'Mobile Scanner App', 'iPad Scanner']),
                        'ip_address' => fake()->ipv4(),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        foreach (array_chunk($regRows, 300) as $chunk) {
            DB::table('registrations')->insert($chunk);
        }
        foreach (array_chunk($attRows, 300) as $chunk) {
            DB::table('attendance')->insert($chunk);
        }
    }

    private function seedSurveyResponses(array $students, array $events): void
    {
        $now = now();

        // Pull the checked_in registrations + their event's post evaluation questions in one go.
        $checkedIn = DB::table('registrations')
            ->join('events', 'events.id', '=', 'registrations.event_id')
            ->where('registrations.status', 'checked_in')
            ->whereNotNull('events.organizer_notes')
            ->where('events.organizer_notes', 'DEMO_SEED_DATA')
            ->select('registrations.user_id', 'registrations.event_id')
            ->get();

        $evaluationsByEvent = DB::table('event_evaluations')
            ->where('evaluation_type', 'post')
            ->get()
            ->keyBy('event_id');

        $questions = DB::table('survey_questions')
            ->whereIn('survey_template_id', DB::table('survey_templates')->where('type', 'post')->pluck('id'))
            ->orderBy('sort_order')
            ->get();

        $rows = [];
        $seenTriples = [];

        foreach ($checkedIn as $reg) {
            if (!fake()->boolean(65)) {
                continue; // not everyone fills the post-survey
            }
            $evaluation = $evaluationsByEvent->get($reg->event_id);
            if (!$evaluation) {
                continue;
            }

            foreach ($questions as $q) {
                $triple = $reg->user_id . '|' . $evaluation->id . '|' . $q->id;
                if (isset($seenTriples[$triple])) {
                    continue;
                }
                $seenTriples[$triple] = true;

                $responseText = null;
                $responseJson = null;

                switch ($q->type) {
                    case 'rating':
                        $responseText = (string) fake()->numberBetween(3, 5);
                        break;
                    case 'multiple_choice':
                        $options = json_decode($q->options, true) ?? [];
                        $responseJson = json_encode([fake()->randomElement($options ?: ['نعم'])], JSON_UNESCAPED_UNICODE);
                        break;
                    case 'checkbox':
                        $options = json_decode($q->options, true) ?? [];
                        $pick = fake()->randomElements($options ?: ['المحتوى'], min(count($options ?: [1]), rand(1, 3)));
                        $responseJson = json_encode($pick, JSON_UNESCAPED_UNICODE);
                        break;
                    case 'textarea':
                        $responseText = fake()->randomElement([
                            'فعالية رائعة ومفيدة جدًا.',
                            'أتمنى المزيد من هذه الفعاليات.',
                            'التنظيم كان جيدًا لكن الوقت كان قصيرًا.',
                            'استفدت كثيرًا، شكرًا لكم.',
                        ]);
                        break;
                    default:
                        $responseText = fake()->sentence(6, false);
                }

                $rows[] = [
                    'id' => (string) Str::uuid(),
                    'user_id' => $reg->user_id,
                    'event_evaluation_id' => $evaluation->id,
                    'question_id' => $q->id,
                    'response_text' => $responseText,
                    'response_json' => $responseJson,
                    'submitted_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($rows, 300) as $chunk) {
            DB::table('survey_responses')->insert($chunk);
        }
    }

    private function seedNotifications(string $adminId, array $events, int $studentCount): void
    {
        $now = now();
        $rows = [];
        $titles = [
            ['ar' => 'تذكير: فعالية غدًا', 'en' => 'Reminder: Event tomorrow'],
            ['ar' => 'تم فتح باب التسجيل', 'en' => 'Registration is now open'],
            ['ar' => 'شكرًا لمشاركتكم', 'en' => 'Thank you for participating'],
            ['ar' => 'تحديث هام بخصوص الفعالية', 'en' => 'Important event update'],
            ['ar' => 'استبيان جديد متاح', 'en' => 'New survey available'],
        ];

        for ($i = 0; $i < 30; $i++) {
            $t = fake()->randomElement($titles);
            $recipientType = fake()->randomElement(['all', 'role', 'event', 'individual']);
            $event = fake()->randomElement($events);

            $rows[] = [
                'id' => (string) Str::uuid(),
                'title_ar' => self::DEMO_TITLE_PREFIX . $t['ar'],
                'title_en' => self::DEMO_TITLE_PREFIX . $t['en'],
                'content_ar' => 'هذا إشعار تجريبي لأغراض اختبار النظام.',
                'content_en' => 'This is a demo notification for system testing.',
                'recipient_type' => $recipientType,
                'user_id' => $recipientType === 'individual' ? $adminId : null,
                'role_id' => null,
                'event_id' => $recipientType === 'event' ? $event['id'] : null,
                'scheduled_at' => $now,
                'delivered_at' => fake()->boolean(80) ? $now : null,
                'status' => fake()->randomElement(['sent', 'sent', 'sent', 'scheduled', 'failed']),
                'delivery_logs' => json_encode(['recipients' => fake()->numberBetween(1, $studentCount)]),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('notifications')->insert($rows);
    }
}
