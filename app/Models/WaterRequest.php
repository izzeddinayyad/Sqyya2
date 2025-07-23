<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'representative_id',
        'point_id',
        'emergency',
        'quantity',
        'status',
        'scheduled_time',
        'current_location',
        'location',
        'scheduled_at',
        'notes',
        'truck_id',
        'driver_id',
        'assigned_at',
    ];

    protected $casts = [
        'emergency' => 'boolean',
        'scheduled_time' => 'datetime',
        'scheduled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function representative()
    {
        return $this->belongsTo(User::class, 'representative_id');
    }

    public function distributionPoint()
    {
        return $this->belongsTo(DistributionPoint::class, 'point_id');
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
} 