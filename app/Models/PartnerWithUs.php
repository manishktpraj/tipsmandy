<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerWithUs extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'partner_with_us';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'phone', 'message'];
}
