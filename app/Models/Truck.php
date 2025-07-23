<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_number',
        'truck_type',
        'tank_capacity',
        'status',
        'driver_id',
        'maintenance_date',
        'institution_id',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function institution()
    {
        return $this->belongsTo(User::class, 'institution_id');
    }
}
