<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $table = "system_settings";
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Fetch a setting value with a fallback default.
     */
    public static function get(string $key, $default = null)
    {
        $value = static::where('key', $key)->value('value');

        return ($value === null || $value === '') ? $default : $value;
    }

    /**
     * Store a setting value.
     */
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
