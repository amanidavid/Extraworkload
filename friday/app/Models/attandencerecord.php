<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attandencerecord extends Model
{
    use HasFactory;
    
    protected $table = 'ratiba';

    protected $fillable = [
        'ratiba_id',
        'enrollment_id',
    ];

    public function ratiba()
    {
        return $this->belongsTo(ratiba::class);
    }

    /**
     * Get the enrollment associated with the attendance sheet.
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
