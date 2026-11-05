<?php

namespace App\Support;

use App\Models\AdminActivityLog;

class AdminLog
{
  public static function record(string $section, string $action, array $changes = []): void
{
    try {
        $u = auth()->user();
        AdminActivityLog::create([
            'admin_id'   => $u?->id,
            'admin_name' => $u?->full_name ?? 'System',
            'section'    => $section,
            'action'     => $action,
            'changes'    => $changes ?: null,
            'ip'         => request()->ip(),
        ]);
    } catch (\Throwable $e) {
        report($e); // never let a logging error break the admin action
    }
}

    /** Logs only if something actually changed. */
    public static function changed(string $section, string $action, array $old, array $new): void
    {
        $diff = [];
        foreach ($new as $key => $value) {
            $before = $old[$key] ?? null;
            if (json_encode($before) !== json_encode($value)) {
                $diff[$key] = ['from' => $before, 'to' => $value];
            }
        }
        if ($diff) {
            self::record($section, $action, $diff);
        }
    }
}