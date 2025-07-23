<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'capacity',
        'status',
    ];

    public function waterRequests()
    {
        return $this->hasMany(WaterRequest::class, 'point_id');
    }
} 