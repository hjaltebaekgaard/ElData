<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeteringPoint extends Model
{
    use HasFactory;

    public $timestamps = false;

    /* Overwriting eloquent 'brilliant' assumptions */
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'user_id', 'type', 'sheet_id', 'collect_enabled', 'transfer_enabled'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dataPoints()
    {
        return $this->hasMany(DataPoint::class);
    }
}
