<?php

namespace App\Models;

use App\Models\UserPerfume;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfume extends Model
{
    use HasFactory;

    public function userPerfumes()
    {
        return $this->hasMany(UserPerfume::class);
    }
}


