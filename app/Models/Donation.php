<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;
    
    protected $fillable = ['id','value', 'unit','percentage','created_at','updated_at'];
}
