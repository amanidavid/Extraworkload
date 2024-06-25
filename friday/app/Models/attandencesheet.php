<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class attandencesheet extends Model
{
    use HasFactory;

    protected $table = 'attandencesheets';

    protected $fillable = [
        'schedule_id',
        'enrollment_id',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Get the enrollment associated with the attendance sheet.
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
