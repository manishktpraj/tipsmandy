<?php

namespace App\Http\Controllers\Admin\Sitepermissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Traits\Admin\FormButtons;
use App\Models\Manager;
use App\Models\Role;
use App\Models\Permission;
use App\Models\SitePermission;
use Exception;
use App\Traits\SitepermissionsTrait;
use App\Traits\Admin\AuthorizationsTrait;

class SitepermissionsController extends Controller
{

    use SitepermissionsTrait, AuthorizationsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($role_id)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'site-permissions', 'is_read'))
        {
            return abort(403);
        }

        $this->setPageTitle('Site Permissions', 'Site Permissions');

        $data['managers'] = Manager::select('id', 'name', 'status')
                    ->where('status', 1)
                    ->orderBy('id', 'desc')
                    ->get();

        $data['roles'] = $this->roles();

        $data['permissions'] = $this->permissions();

        $data['sitepermissions'] = SitePermission::where('role_id', $role_id)->get();

        $data['id'] = $role_id;

        $current_role = '';

        if(!empty($role_id)){
            $role = Role::where('name', '!=', 'Super Admin')->whereId($role_id)->first();
            $current_role = $role->name;
        }

        $data['current_role'] = $current_role;

        return view('admin.sitepermissions.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return SitePermission::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function permissionstatus(Request $request)
    {
        if($request->ajax()) {

            //Check authorization
            if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'site-permissions', 'is_edit'))
            {
                $response = [
                    'error' => 'You are not authorised for this action.',
                ];

                return response()->json($response);
            }

            $columnsArray = [
                1 => 'is_read',
                2 => 'is_add',
                3 => 'is_edit',
                4 => 'is_delete',
                5 => 'is_status',
            ];

            $role_id = request('role_id');
            $id = request('site_permission_id');
            $status = $columnsArray[request('status')];
            $current_status = request('current_status');
            $permission = SitePermission::where('id', $id)->where($status, $current_status)->first(["id", $status])->toArray();

            //Status
            $new_status = true;

            if($permission[$status]){

                $new_status = false;

            }

            $site_status = $status;

            $update = SitePermission::where('id', $id)->update([$status => $new_status]);

            if($update) {

                $is_read = '';

                if($current_status==1){
                    $current_status = 0;
                }

                if($current_status==false){
                    $current_status = 1;
                }

                $detail = SitePermission::where('id', $id)->where($status, $new_status)->first(["id", $status])->toArray();

                $btn_class = $detail[$status] == 1 ? 'success' : 'danger';

                $btn_status = $detail[$status] == 1 ? '1' : '0';

                $btn_value = $detail[$status] ? 'Yes' : 'No';

                $is_read .= "<a href='javascript:;' onclick='updatesitepermissions(".$role_id.", ".$id.", ".request('status').", ".$btn_status.")'><span class='btn btn-sm btn-".$btn_class."'>".$btn_value."</span></a>";

                $response = [
                    'is_read' => $is_read,
                    'success' => 'Status updated successfully.',
                ];

                return response()->json($response);
            }

            $response = [
                'error' => 'Oops. something went wrong. please try again.',
            ];

            return response()->json($response);
        }

        //Ajax request type is invalid
        return $this->invalidajaxRequest();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateallstatus(Request $request, $status, $role_id)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'site-permissions', 'is_edit'))
        {
            return abort(403);
        }
        //Get status
        $count = SitePermission::where($status, true)->where('role_id', $role_id)->count();

        $new_status = $count ? false : true;

        $update = SitePermission::where('role_id', $role_id)->update([$status => $new_status]);

        return back()->with('success', 'Status updated successfully.');
    }
}
