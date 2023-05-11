<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'price', 'length'
    ];

    public function apartments() {
        return $this->belongsToMany(Apartment::class);
    }
}
