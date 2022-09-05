<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'description',
        'phone_number',
        'address',
        'logo_image_path',
        'website',
        'enabled',
        'premium',
        'reviews_count',
        'rating'
    ];


    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function favourites()
    {
        return $this->hasMany('App\Models\Favourite');
    }

}
