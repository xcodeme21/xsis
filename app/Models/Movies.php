<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Movies extends Model
{
    protected $table = 'movies';
    protected $fillable = ['title', 'description', 'rating', 'image'];

    public function getMoviesImageUrlAttribute()
    {
        return Storage::url('images/movies/' . $this->id . '/' . $this->image);
    }
}
