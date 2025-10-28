<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gifts extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'total',
        'enabled'
    ];

    protected $casts = [
        'enabled' => 'boolean'
    ];

    // Relationship with UserGift
    public function userGifts()
    {
        return $this->hasMany(UserGift::class, 'gift_id');
    }

    // Get count of selected gifts
    public function getSelectedCountAttribute()
    {
        return $this->userGifts()->count();
    }

    // Get available count (total - selected)
    public function getAvailableCountAttribute()
    {
        return max(0, $this->total - $this->getSelectedCountAttribute());
    }

    // Scope for enabled gifts
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    // Scope for disabled gifts
    public function scopeDisabled($query)
    {
        return $query->where('enabled', false);
    }
}
