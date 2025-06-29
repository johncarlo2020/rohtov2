<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
       'alliance_bank','redeem_date','email_consent','sms_consent','utm_medium','utm_source','type','guess','is_appointment','lname', 'email','fname','number','password','last_login_at','dob','country','otp','otp_verified','task_2_image','task_3_image'
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

    public function stations()
    {
        return $this->belongsToMany(Station::class, 'station_users')->withPivot('time_spent', 'created_at');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function stationUser()
    {
        return $this->hasMany(StationUser::class);
    }

    public function userAppointments()
    {
        return $this->hasMany(UserAppointment::class);
    }
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'user_tasks', 'user_id', 'task_id')
                    ->withPivot('status');
    }

    public function products()
    {
        return $this->belongsToMany(Products::class, 'user_products', 'user_id', 'product_id')->withTimestamps();
    }


}
