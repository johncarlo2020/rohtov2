<?php

namespace App\Models;

use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_id',
        'user_id',
        'claimed_at',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    protected $casts = [
        'claimed_at' => 'datetime',
    ];
}
