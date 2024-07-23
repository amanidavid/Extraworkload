<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lecturerlevel extends Model
{
    use HasFactory;

    protected $table ='lecturerlevels';
    protected $fillables =['user_id','level_id'];

    public function level(){
        return $this->belongsTo(level::class,'level_id');
    }

    public function user(){
        return $this->belongsTo(user::class,'user_id');
    }
}
