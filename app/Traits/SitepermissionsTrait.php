<?php

namespace App\Traits;

use App\Models\Manager;
use App\Models\Role;
use App\Models\Permission;
use App\Models\SitePermission;

trait SitepermissionsTrait
{
    /**
     * Get all managers list
     * @return \Illuminate\Http\Response
     */
    public function managers()
    {
        return Manager::select('id', 'languagecode_id', 'name', 'status', 'slug')
                ->where('status', 1)
                ->get();
    }


    /**
     * Get all permissions list
     * @return \Illuminate\Http\Response
     */
    public function permissions()
    {

        return Permission::all();
    }

    /**
     * Get all roles list
     * @return \Illuminate\Http\Response
     */
    public function roles()
    {

        return Role::all();
    }


    public function checksitepermission($id=null, $role, $manager)
    {
        return SitePermission::where('id', '!=', $id)->where('role_id', $role)->where('manager_id', $manager)->count();
    }
}
