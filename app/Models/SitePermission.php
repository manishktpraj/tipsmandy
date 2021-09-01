<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePermission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'site_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded  = [];


    /**
     * Get the user that owns the role.
     */
    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id')->withDefault([
            'name' => '-'
        ]);
    }

    /**
     * Get the user that owns the role.
     */
    public function manager()
    {
        return $this->belongsTo('App\Models\Manager', 'manager_id')->withDefault([
            'name' => '-'
        ]);
    }
}
