<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeveloperUser extends Model
{
    use HasFactory;

    protected $table = 'developer_user'; // 👈 your pivot table

    protected $fillable = [
        'user_id',
        'developer_id',
        'isCompleted',
    ];

    public $timestamps = true;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }
}
