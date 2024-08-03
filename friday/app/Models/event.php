<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class event extends Model
{
    use HasFactory;
    
    protected $table = 'events';
    
    protected $fillable =['event_name'];
    // In Record.php (assuming this is the model for your records)
public function event()
{
    return $this->belongsTo(Event::class);
}

}
