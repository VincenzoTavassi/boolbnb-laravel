<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'name', 'text'
    ];

    public function apartment() {
        return $this->belongsTo(Apartment::class);
    }
}
