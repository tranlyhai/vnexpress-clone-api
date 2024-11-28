<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;


class Post extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'thumbnail',
        'user_id',
        'category_ids',
        'view'
    ];

}