<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendEmailRequest;
use App\Services\MailService;
use Illuminate\Http\JsonResponse;

class EmailApiController extends Controller
{
    public function __construct(
        private readonly MailService $mailService
    ) {}

    public function send(SendEmailRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $success = $this->mailService->sendEmail(
            $validated['recipient'],
            $validated['subject'],
            $validated['body'],
            $validated['extra_data'] ?? []
        );

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'فشل إرسال البريد الإلكتروني. يرجى المحاولة لاحقاً.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال البريد الإلكتروني بنجاح.'
        ]);
    }
}
