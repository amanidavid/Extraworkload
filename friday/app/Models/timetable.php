<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'venue_id',
        'start_time_id',
        'start_time_id',
    ];
}
