<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'city',
        'institution_id',
        'status'
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

    public function stations()
    {
        return $this->hasMany(\App\Models\Station::class);
    }

    public function truck()
    {
        return $this->hasOne(\App\Models\Truck::class, 'driver_id');
    }

    public function institution()
    {
        return $this->belongsTo(User::class, 'institution_id');
    }

    public function institutionMembers()
    {
        return $this->hasMany(User::class, 'institution_id');
    }

    public function trucks()
    {
        return $this->hasMany(\App\Models\Truck::class, 'institution_id');
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\WaterRequest::class, 'representative_id');
    }

    /**
     * Check if user is an institution owner
     */
    public function isInstitutionOwner()
    {
        return $this->role === 'org_owner' && $this->institution_id === null;
    }

    /**
     * Check if user is a driver
     */
    public function isDriver()
    {
        return $this->role === 'driver';
    }

    /**
     * Check if user is a representative
     */
    public function isRepresentative()
    {
        return $this->role === 'representative';
    }

    /**
     * Get the institution ID for the current user
     */
    public function getInstitutionId()
    {
        return $this->institution_id ?? $this->id;
    }
}
