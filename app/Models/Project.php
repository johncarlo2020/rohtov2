<?php

namespace App\Models;

use App\Models\Developer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'developer_id',
        'name',
        'address',
    ];

    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }

    /**
     * Get wrong answers (same developer)
     */
    public function getWrongAnswers($limit = 3)
    {
        return self::where('developer_id', $this->developer_id)
            ->where('id', '!=', $this->id)
            ->inRandomOrder()
            ->limit($limit)
            ->pluck('name')
            ->toArray();
    }

    /**
     * Quiz options
     */
    public function getQuizOptions()
    {
        return collect([$this->name])
            ->merge($this->getWrongAnswers())
            ->shuffle()
            ->values();
    }
}
