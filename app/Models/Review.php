<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'university_id',
        'user_name',
        'review_comment',
        'rating',
        'updated_at'
    ];

    public function university()
    {
        return $this->belongsTo('App\Models\University');
    }
}
