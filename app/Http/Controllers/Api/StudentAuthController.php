<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Services\MailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentAuthController extends Controller
{
    public function __construct(
        protected MailService $mailService
    ) {}

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'nullable|string|in:male,female',
            'academic_year' => 'nullable|string|max:50',
            'school_name' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|in:scientific,literary,علمي,أدبي',
        ]);

        $apiToken = Str::random(60);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'api_token' => $apiToken,
            'phone_number' => $validated['phone_number'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'academic_year' => $validated['academic_year'] ?? null,
            'school_name' => $validated['school_name'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'status' => 'active',
            'preferred_language' => 'ar',
            'preferred_theme' => 'light',
            'timezone' => config('app.timezone', 'Africa/Tripoli'),
        ]);

        // Assign Student role if it exists
        try {
            $user->assignRole('Student');
        } catch (\Throwable $e) {
            // Fallback if Spatie roles aren't seeded yet
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الحساب بنجاح / Account registered successfully.',
            'student' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'api_token' => $user->api_token,
            ]
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات الدخول غير صحيحة / Invalid credentials.'
            ], 401);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'الحساب غير نشط / Account is inactive.'
            ], 403);
        }

        $isFirstLogin = is_null($user->last_login_at);

        $updates = ['last_login_at' => now()];
        // Generate new API token on login if not exists
        if (!$user->api_token) {
            $updates['api_token'] = Str::random(60);
        }
        $user->update($updates);

        if ($isFirstLogin) {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\WelcomeEmail($user));
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح / Logged in successfully.',
            'student' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'api_token' => $user->api_token,
            ]
        ]);
    }

    public function googleLogin(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_token' => 'required|string',
        ]);

        try {
            $auth = app('firebase.auth');
            $verifiedIdToken = $auth->verifyIdToken($validated['id_token']);
            
            $email = $verifiedIdToken->claims()->get('email');
            $name = $verifiedIdToken->claims()->get('name') ?? 'مستخدم جوجل';
            $picture = $verifiedIdToken->claims()->get('picture');

            if (!$email) {
                return response()->json([
                    'success' => false,
                    'message' => 'تعذر الحصول على البريد الإلكتروني من حساب جوجل / Cannot retrieve email from Google.'
                ], 400);
            }

            $user = User::where('email', $email)->first();
            $isFirstLogin = false;

            if (!$user) {
                $apiToken = Str::random(60);
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make(Str::random(24)),
                    'api_token' => $apiToken,
                    'avatar' => $picture,
                    'status' => 'active',
                    'preferred_language' => 'ar',
                    'preferred_theme' => 'light',
                    'timezone' => config('app.timezone', 'Africa/Tripoli'),
                    'last_login_at' => now(),
                ]);

                try {
                    $user->assignRole('Student');
                } catch (\Throwable $e) {}

                $isFirstLogin = true;
            } else {
                $isFirstLogin = is_null($user->last_login_at);
                $updates = ['last_login_at' => now()];
                if ($picture && !$user->avatar) {
                    $updates['avatar'] = $picture;
                }
                if (!$user->api_token) {
                    $updates['api_token'] = Str::random(60);
                }
                $user->update($updates);
            }

            if ($isFirstLogin) {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\WelcomeEmail($user));
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الدخول بنجاح / Logged in successfully.',
                'student' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'api_token' => $user->api_token,
                    'avatar' => $user->avatar,
                ]
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'رمز المصادقة غير صالح / Invalid authentication token.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->update(['api_token' => null]);

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الخروج بنجاح / Logged out successfully.'
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $email = $request->validated()['email'];
        $code = (string) random_int(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => Hash::make($code), 'created_at' => now()]
        );

        $this->mailService->sendEmail(
            $email,
            'رمز إعادة تعيين كلمة المرور - منصة وجهة',
            "<p>رمز إعادة تعيين كلمة المرور الخاص بك هو:</p><h2 style=\"letter-spacing:4px;\">{$code}</h2><p>هذا الرمز صالح لمدة 60 دقيقة. إذا لم تطلب إعادة تعيين كلمة المرور، يمكنك تجاهل هذه الرسالة.</p>"
        );

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال رمز إعادة تعيين كلمة المرور إلى بريدك الإلكتروني / Password reset code sent to your email.'
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $record = DB::table('password_reset_tokens')->where('email', $validated['email'])->first();

        $expireMinutes = config('auth.passwords.users.expire', 60);

        if (!$record
            || !Hash::check($validated['code'], $record->token)
            || now()->diffInMinutes($record->created_at) > $expireMinutes) {
            return response()->json([
                'success' => false,
                'message' => 'رمز إعادة التعيين غير صالح أو منتهي الصلاحية / Reset code is invalid or expired.'
            ], 400);
        }

        $user = User::where('email', $validated['email'])->first();
        $user->update([
            'password' => Hash::make($validated['password']),
            'api_token' => Str::random(60),
        ]);

        DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم إعادة تعيين كلمة المرور بنجاح / Password reset successfully.',
            'student' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'api_token' => $user->api_token,
            ]
        ]);
    }
}
