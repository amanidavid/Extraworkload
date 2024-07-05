<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lecture_in extends Model
{
    use HasFactory;
    protected $fillable =['module_id','lecturerlevels_id'];
}
