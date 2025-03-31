<?php

namespace App\Models;

use Carbon\Traits\LocalFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use LocalFactory;
    protected $fillable = [
        'name',
    ];
}
