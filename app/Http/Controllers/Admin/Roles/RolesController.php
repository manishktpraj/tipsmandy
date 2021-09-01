<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Traits\Admin\FormButtons;
use App\Traits\Admin\AuthorizationsTrait;
use App\Models\Role;
use App\Models\Permission;
use App\Models\SitePermission;
use App\Models\Manager;
use App\Admin;
use Exception;

class RolesController extends Controller
{
    use AuthorizationsTrait, FormButtons;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'roles', 'is_read'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Roles', 'Manage Roles');

        $renderButtons = $this->addButtons(route('admin.roles.create'), 'roles', 'Add Role');

        $roles = Role::orderBy('id', 'asc')->get();

        return view('admin.roles.index', compact('roles', 'renderButtons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'roles', 'is_add'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Roles', 'Create Role');

        $renderButtons = $this->addFormButtons(route('admin.roles.index'));

        $backButtons = $this->backButtons(route('admin.roles.index'));

        return view('admin.roles.create', compact('renderButtons', 'backButtons'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'roles', 'is_add'))
        {
            return abort(403);
        }

        //Validation request data
        $request->validate([
            'name' => 'required|string|max:191|unique:roles,name',
        ]);

        $data['name'] = $slug = $request->name ? : NULL;
        $data['slug'] = Str::slug($slug, '-');
        $create = Role::create($data);

        if($create) {

            $managers = Manager::select('id')->where('status', 1)->get();
            foreach($managers as $manager) {
                SitePermission::create([
                    'role_id' => $create->id,
                    'manager_id' => $manager->id,
                ]);
            }
            return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
        }

        return redirect()->back()->with('error', 'Oops. Something went wrong. Please try again.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'roles', 'is_edit'))
        {
            return abort(403);
        }
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
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'roles', 'is_edit'))
        {
            return abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'roles', 'is_delete'))
        {
            return abort(403);
        }

        SitePermission::where('role_id', $id)->delete();

        Admin::where('is_role', $id)->delete();

        Role::where('id', $id)->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
