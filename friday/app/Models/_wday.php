<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _wday extends Model
{
    use HasFactory;
    protected $fillable = [
        'weekday_name',
       
    ];
}
