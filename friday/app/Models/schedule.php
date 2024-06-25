<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'wdays_id',
        'module_id',
        'venue_id',
        'start_time_id',
        'start_time_id',
    ];
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class, 'wdays_id');
    }

    public function startClock()
    {
        return $this->belongsTo(Clock::class, 'start_time_id');
    }

    public function endClock()
    {
        return $this->belongsTo(Clock::class, 'end_time_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function attandencesheets()
    {
        return $this->hasMany(Attandencesheet::class);
    }
}
