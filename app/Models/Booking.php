<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function dropOffPoint()
    {
        return $this->belongsTo(DropOffPoint::class);
    }

    public function seats()
    {
        return $this->hasMany(BookingSeat::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($booking) {
            $booking->code = 'BK' . strtoupper(substr(uniqid(), -6));
        });
    }
}
