<?php

namespace App\Services;

use App\Models\HistoryLog;
use Illuminate\Support\Facades\Auth;

class HistoryLogService
{
    /**
     * Record a new history log entry.
     *
     * @param string $action
     * @param string $description
     * @param string|null $targetType
     * @param int|string|null $targetId
     * @return HistoryLog
     */
    public static function log(string $action, string $description, ?string $targetType = null, $targetId = null): HistoryLog
    {
        $user = Auth::user();

        $userId = $user ? $user->id : null;
        $userName = $user ? trim(($user->fname ?? '') . ' ' . ($user->lname ?? '')) : 'System / Guest';
        if (empty(trim($userName)) && $user) {
            $userName = $user->name ?? $user->email;
        }
        
        $roleName = 'guest';
        if ($user) {
            if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                $roleName = 'superadmin';
            } elseif (method_exists($user, 'getRoleNames') && $user->getRoleNames()->isNotEmpty()) {
                $roleName = $user->getRoleNames()->first();
            } else {
                $roleName = 'client';
            }
        }

        return HistoryLog::create([
            'user_id' => $userId,
            'user_name' => $userName,
            'user_role' => $roleName,
            'action' => strtoupper($action),
            'description' => $description,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'ip_address' => request()->ip(),
        ]);
    }
}
