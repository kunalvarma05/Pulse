<?php

namespace Pulse\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{

    /**
     * Table
     */
    protected $table = "providers";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'title', 'description', 'link', 'picture', 'alias',
    ];
}
