<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //

    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];
}
