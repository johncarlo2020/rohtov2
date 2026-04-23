<?php

namespace App\Models;

use App\Models\Answer;
use App\Models\Developer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('is_correct');
    }
}
