<?php

namespace App\Http\Controllers\API\Concerns;

use App\Models\User;
use Illuminate\Http\Request;

trait ResolvesApiUser
{
    protected function resolveApiUser(Request $request): ?User
    {
        $authUser = $request->user();
        if ($authUser) {
            return $authUser;
        }

        // Fallback solo para desarrollo local/testing si no hay auth activa.
        if (! app()->environment(['local', 'testing'])) {
            return null;
        }

        $userKey = $request->query('user', $request->header('X-User'));
        if (! $userKey) {
            return null;
        }

        if (is_numeric($userKey)) {
            return User::query()->find((int) $userKey);
        }

        $exact = User::query()
            ->where('name', $userKey)
            ->orWhere('email', $userKey)
            ->first();

        if ($exact) {
            return $exact;
        }

        $normalizedLookup = $this->normalizeUserKey($userKey);

        return User::query()
            ->get()
            ->first(function (User $user) use ($normalizedLookup) {
                return $this->normalizeUserKey($user->name) === $normalizedLookup
                    || $this->normalizeUserKey($user->email) === $normalizedLookup;
            });
    }

    private function normalizeUserKey(string $value): string
    {
        $normalized = mb_strtolower(trim($value), 'UTF-8');
        $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $normalized);

        return preg_replace('/[^a-z0-9]/', '', $transliterated ?: $normalized) ?: '';
    }
}
