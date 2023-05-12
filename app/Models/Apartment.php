<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'image', 'price', 'single_beds', 'double_beds', 'bathrooms', 'square_meters', 'rooms', 'address', 'latitude', 'longitude', 'visible'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class)->withPivot(['start_date', 'end_date']);;
    }
}
