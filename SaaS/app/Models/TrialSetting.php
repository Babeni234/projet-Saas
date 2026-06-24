<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrialSetting extends Model
{
    protected $table = 'trial_settings';

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    /**
     * Get value of a setting by its key.
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
