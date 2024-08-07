<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class record extends Model
{
    use HasFactory;
    protected $table = 'records';

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
  

   

    /**
     * Get the enrollment associated with the attendance sheet.
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id')->withDefault();
    }
}
