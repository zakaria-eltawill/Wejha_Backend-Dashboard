<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterForEventRequest;
use App\Services\RegistrationService;
use Illuminate\Http\JsonResponse;

class EventBookingController extends Controller
{
    public function __construct(
        protected RegistrationService $registrationService
    ) {}

    public function register(RegisterForEventRequest $request): JsonResponse
    {
        try {
            $registration = $this->registrationService->register(
                auth()->id(),
                $request->input('event_id'),
                $request->input('source', 'web')
            );

            return response()->json([
                'success' => true,
                'message' => 'تم التسجيل في الفعالية بنجاح!',
                'registration' => [
                    'id' => $registration->id,
                    'status' => $registration->status,
                    'qr_hash' => $registration->qr_hash,
                ]
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 400);
        }
    }

    public function cancel(string $id): JsonResponse
    {
        try {
            $cancelled = $this->registrationService->cancel($id);

            if ($cancelled) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إلغاء التسجيل بنجاح.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'تعذر إلغاء التسجيل.'
            ], 400);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
