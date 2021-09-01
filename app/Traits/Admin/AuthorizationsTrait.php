<?php

namespace App\Traits\Admin;

use Illuminate\Support\Facades\DB;
use App\Models\Manager;
use App\Models\Role;
use App\Models\Permission;
use App\Models\SitePermission;

trait AuthorizationsTrait
{
    /**
     * Check site permissions
     * @return true or false
     */
    public function checkPermission($role_id, $manager, $permission)
    {

        $role = Role::where('id', $role_id)->where('name' , '!=', 'Super Admin')->count();

        if($role){

          $permission_count = DB::table('managers')
                             ->join('site_permissions', 'site_permissions.manager_id', '=', 'managers.id')
                             ->where('site_permissions.role_id', $role_id)
                             ->where('managers.slug', $manager)
                             ->where('site_permissions.'.$permission, 1)
                             ->count();

          if($permission_count){

              return true;

          }else{

              return false;
          }

        }

        return true;
    }

}
