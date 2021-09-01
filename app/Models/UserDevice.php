<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_devices';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'device_id', 'device_token', 'device_type', 'lat', 'lng'];
}
