<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    /**
     * @var string
     */
    protected $table = 'sources';

    /**
     * @var array
     */
    protected $fillable = ['name'];
}
