<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'email', 'name', 'text', 'apartment_id'
    ];

    public function apartment() {
        return $this->belongsTo(Apartment::class);
    }
}
