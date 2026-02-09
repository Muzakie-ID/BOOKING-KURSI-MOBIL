<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $guarded = [];

    public function dropOffPoints()
    {
        return $this->hasMany(DropOffPoint::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
