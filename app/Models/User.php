<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Developer;
use App\Models\VoucherClaim;
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
        'name',
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
        'title',
        'fname',
        'lname',
        'email',
        'preferred_contact',
        'communication_consent',
        'marketing',
        'number',
        'find',
        'dob',
        'password',
        'last_login_at',
        'race',
        'country',
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
    ];



    public function stationUser()
    {
        return $this->hasMany(StationUser::class);
    }

    public function userGift()
    {
        return $this->hasOne(UserGift::class);
    }

    public function voucherClaims()
    {
        return $this->hasMany(VoucherClaim::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_email', 'email');
    }

    /**
     * Check if the user is a super admin
     */
    public function isSuperAdmin()
    {
        $superAdminEmails = ['admin@gmail.com', 'superadmin@gmail.com'];
        return in_array(strtolower($this->email), $superAdminEmails) || $this->hasRole('superadmin');
    }

    /**
     * Check if the user has staff role
     */
    public function isStaff()
    {
        return $this->hasRole('staff');
    }

    /**
     * Check if the user is any admin, superadmin, or staff member
     */
    public function isAdminOrStaff()
    {
        return $this->isSuperAdmin() || $this->hasRole('admin') || $this->hasRole('staff');
    }

    /**
     * Check if the user is a protected admin user
     */
    public function isProtectedAdmin()
    {
        $protectedEmails = ['admin@gmail.com', 'superadmin@gmail.com', 'manager@gmail.com', 'support@gmail.com'];
        
        return in_array(strtolower($this->email), $protectedEmails) || $this->isSuperAdmin() || $this->hasRole('admin') || $this->hasRole('staff');
    }
}
