<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutualFund extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mutual_funds';

    /**
     * @var array
     */
    protected $fillable = ['title'];
}
