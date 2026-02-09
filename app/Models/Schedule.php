<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $guarded = [];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
