<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharmConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'charm_count',
        'is_enabled'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'charm_count' => 'integer'
    ];
}
