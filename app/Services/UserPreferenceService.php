<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserPreferenceService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function getPreference(User $user, string $key, mixed $default = null): mixed
    {
        if ($key === 'preferred_language') {
            return $user->preferred_language;
        }
        if ($key === 'preferred_theme') {
            return $user->preferred_theme;
        }
        if ($key === 'timezone') {
            return $user->timezone;
        }

        $prefs = $user->notification_preferences ?? [];
        return $prefs[$key] ?? $default;
    }

    public function setPreference(User $user, string $key, mixed $value): bool
    {
        if ($key === 'preferred_language') {
            $user->preferred_language = (string) $value;
        } elseif ($key === 'preferred_theme') {
            $user->preferred_theme = (string) $value;
        } elseif ($key === 'timezone') {
            $user->timezone = (string) $value;
        } else {
            $prefs = $user->notification_preferences ?? [];
            $prefs[$key] = $value;
            $user->notification_preferences = $prefs;
        }

        return $user->save();
    }
}
