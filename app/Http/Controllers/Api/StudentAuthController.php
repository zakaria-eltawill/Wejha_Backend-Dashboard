<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentAuthController extends Controller
{
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
            'timezone' => 'Asia/Riyadh',
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

        // Generate new API token on login if not exists
        if (!$user->api_token) {
            $user->update(['api_token' => Str::random(60)]);
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
}
