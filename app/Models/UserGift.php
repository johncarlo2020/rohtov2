<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGift extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gift_id',
        'redeemed_at',
        'is_redeemed'
    ];

    protected $casts = [
        'redeemed_at' => 'datetime',
        'is_redeemed' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gift()
    {
        return $this->belongsTo(Gifts::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
