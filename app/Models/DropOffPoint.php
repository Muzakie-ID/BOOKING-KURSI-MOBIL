<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropOffPoint extends Model
{
    protected $guarded = [];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
