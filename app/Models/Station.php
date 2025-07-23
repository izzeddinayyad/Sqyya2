<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'location', 'daily_capacity', 'status', 'drivers', 'institution_id', 'coordinates', 'image', 'city', 'utilization', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function institution()
    {
        return $this->belongsTo(User::class, 'institution_id');
    }
} 