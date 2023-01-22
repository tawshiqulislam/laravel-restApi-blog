<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Blog extends Model
{
    use HasFactory;

    protected $table='blogs';

    protected $fillable = [
        'title',
        'body',
        'creator_id'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            user::class, 
            'creator_id', 
            'id'
        );
    }

    public function image(): MorphOne
    {
        return $this->morphOne(
            Image::class,
            'owns',
            'image_type',
            'image_id',
            'id'
        );
    }
}
