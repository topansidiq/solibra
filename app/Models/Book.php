<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'year',
        'isbn',
        'stock',
        'description',
        'cover'
    ];

    public function getInitialAttribute()
    {
        return strtoupper(substr($this->title, 0, 1));
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
