<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class lecture_in extends Model
{
    use HasFactory;
    

    protected $table = 'lecture_ins_tables';
    protected $fillable =['module_id','lecturerlevels_id'];

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
