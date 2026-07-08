<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScanAttendanceRequest;
use App\Services\AttendanceService;
use Illuminate\Http\JsonResponse;

class AttendanceApiController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {}

    public function scan(ScanAttendanceRequest $request): JsonResponse
    {
        try {
            $attendance = $this->attendanceService->recordAttendance(
                $request->input('qr_hash'),
                auth()->id(), // Scanner user
                $request->input('device', 'Web Camera'),
                $request->ip(),
                $request->input('event_id')
            );

            $registration = $attendance->registration;

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الحضور بنجاح!',
                'attendee' => [
                    'name' => $registration->user->name,
                    'email' => $registration->user->email,
                    'school' => $registration->user->school_name ?? 'غير محدد',
                    'time' => $attendance->scan_time->format('H:i:s'),
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 400);
        }
    }
}
