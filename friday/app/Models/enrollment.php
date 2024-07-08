<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'uid_id'
    ];

    // public function attandencesheets()
    // {
    //     return $this->hasMany(Attandencesheet::class);
    // }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
