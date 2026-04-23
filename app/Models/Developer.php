<?php

namespace App\Models;

use App\Models\Project;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('isCompleted');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
