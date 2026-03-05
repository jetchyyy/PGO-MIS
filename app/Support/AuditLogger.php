<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditLogger
{
    public static function log(?int $userId, string $event, ?Model $subject = null, array $context = [], ?string $ip = null, ?string $agent = null): void
    {
        $log = new AuditLog([
            'user_id' => $userId,
            'event' => $event,
            'context' => $context,
            'ip_address' => $ip,
            'user_agent' => $agent,
        ]);

        if ($subject) {
            $log->subject()->associate($subject);
        }

        $log->save();
    }
}
