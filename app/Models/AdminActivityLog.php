<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AdminActivityLog extends Model
{
    protected $fillable = [
        'admin',
        'action',
        'target_type',
        'target_id',
        'description',
        'meta',
        'ip',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * Record an admin action. Never throws: a logging failure must not block the action itself.
     */
    public static function record(string $action, string $description, ?Model $target = null, array $meta = []): ?self
    {
        try {
            if (!Schema::hasTable('admin_activity_logs')) {
                return null;
            }

            $admin = auth()->user();

            return static::create([
                'admin'       => $admin?->email ?? $admin?->name ?? 'admin',
                'action'      => $action,
                'target_type' => $target ? class_basename($target) : null,
                'target_id'   => $target?->getKey(),
                'description' => $description,
                'meta'        => $meta ?: null,
                'ip'          => request()?->ip(),
            ]);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
