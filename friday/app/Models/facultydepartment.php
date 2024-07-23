<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class facultydepartment extends Model
{
    use HasFactory;

    protected $table ='facultys_departments';

    protected $fillable = ['faculty_id','department_id'];

    public function department(){
        return $this->belongsTo(department::class,'department_id');
    }

    public function faculty(){
        return $this->belongsTo(department::class,'faculty_id');
    }
}
