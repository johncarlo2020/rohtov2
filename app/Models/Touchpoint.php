<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Touchpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'label',
        'required_touches',
    ];

    protected $casts = [
        'required_touches' => 'integer',
    ];
}
