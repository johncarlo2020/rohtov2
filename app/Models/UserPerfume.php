<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPerfume extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'perfume_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function perfume()
    {
        return $this->belongsTo(Perfume::class);
    }
}
