<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            return $next($request);
        }

        $token = $request->bearerToken() ?: $request->input('api_token');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'معرّف الوصول غير متوفر / Unauthorized (API Token missing).'
            ], 401);
        }

        $user = User::where('api_token', $token)->where('status', 'active')->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'معرّف الوصول غير صالح / Unauthorized (Invalid API Token).'
            ], 401);
        }

        // Authenticate the user for the current request context
        Auth::login($user);

        return $next($request);
    }
}
