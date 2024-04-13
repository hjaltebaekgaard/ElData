<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPoint extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['metering_point_id', 'time_start', 'quantity', 'quality'];

    protected $casts = [
        'time_start' => 'datetime',
    ];

    public function meteringPoint()
    {
        return $this->belongsTo(MeteringPoint::class);
    }
}
