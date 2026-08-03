<?php

namespace App\Models;

use App\Models\Gifts;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftStockLog extends Model
{
    protected $fillable = [
        'gift_id',
        'user_id',
        'action',
        'quantity',
        'stock_before',
        'stock_after'
    ];

    public function gift()
    {
        return $this->belongsTo(Gifts::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
