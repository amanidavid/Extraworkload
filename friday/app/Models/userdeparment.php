<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class userdeparment extends Model
{
    use HasFactory;
    
    protected $table ='user_departments_tables';
    protected $fillable = ['user_id','department_id'];

    public function user(){
        return $this->belongsTo(user::class,'user_id');
    }
    
    public function department(){
        return $this->belongsTo(department::class,'department_id');
    }
}
