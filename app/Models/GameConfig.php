<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'max_weight',
        'increment_grams',
        'is_active'
    ];

    protected $casts = [
        'max_weight' => 'decimal:1',
        'increment_grams' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get the active game configuration
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first() ?? self::getDefault();
    }

    /**
     * Get default configuration
     */
    public static function getDefault()
    {
        return (object) [
            'max_weight' => 4.0,
            'increment_grams' => 100
        ];
    }

    /**
     * Set this config as active and deactivate others
     */
    public function setAsActive()
    {
        // Deactivate all other configs
        self::where('id', '!=', $this->id)->update(['is_active' => false]);

        // Activate this config
        $this->update(['is_active' => true]);
    }
}
