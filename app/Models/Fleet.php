<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    protected $guarded = [];

    protected $casts = [
        'row_layout' => 'array',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
