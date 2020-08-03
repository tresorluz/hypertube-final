<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchedMovie extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'watched_movies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'id_movie', 'name', 'hash'
    ];
}
