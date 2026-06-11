<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Developer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'heard',
        'code',
        'follow',
        'appeal',
        'existing',
        'social_media',
        'existing', 
        'email',
        'otp',
        'company',
        'otp_verified',
        'fname',
        'lname',
        'number',
        'find',
        'dob',
        'password',
        'last_login_at',
        'race',
        'country',
        'baby_img',
        'baby_name',
        'charname',
        'marketing',
        'property_budget',
        'is_early_bird',
        'chagee_redeemed',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'marketing' => 'boolean',
    ];



    public function stationUser()
    {
        return $this->hasMany(StationUser::class);
    }

    public function userGift()
    {
        return $this->hasOne(UserGift::class);
    }

    public function developers()
    {
        return $this->belongsToMany(Developer::class)
            ->withPivot('isCompleted')
            ->withTimestamps();
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class,'user_question')
            ->withPivot('is_correct')
            ->withTimestamps();
    }

    /**
     * Check if the user is a protected admin user
     */
    public function isProtectedAdmin()
    {
        $protectedEmails = ['admin@gmail.com', 'superadmin@gmail.com', 'manager@gmail.com', 'support@gmail.com'];
        
        return in_array($this->email, $protectedEmails) || $this->hasRole('admin');
    }
}
