<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'managers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $guarded  = [];

    /**
     * @var array
     */
    protected $fillable = ['name', 'slug', 'status'];
}
