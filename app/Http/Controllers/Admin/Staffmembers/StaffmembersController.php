<?php

namespace App\Http\Controllers\Admin\Staffmembers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Admin;
use App\Http\Requests\Admin\Staff\StoreMember;
use App\Http\Requests\Admin\Staff\UpdateMember;
use App\Traits\Admin\FormButtons;
use App\Traits\Admin\AuthorizationsTrait;
use App\Models\Role;
use Exception;
use Helper;
use Profile;

class StaffmembersController extends Controller
{
    use FormButtons, AuthorizationsTrait;

    protected $tbl;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->tbl = new Admin;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'staff-members', 'is_read'))
        {
            //return redirect()->route('admin.dashboard')->with('warning', 'You are not authorised to access that location.');
            //return redirect()->route('admin.dashboard');
            return abort(403);
        }

        $this->setPageTitle('Manage Staff Members', 'Manage Staff Members');

        $renderButtons = $this->addButtons(route('admin.staffmembers.create'), 'staff-members', 'Add Staff Member');

        $roles = Role::all();

        return view('admin.staffmembers.index', compact('renderButtons', 'roles'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStaffMembers(Request $request)
    {
        if(request()->ajax()) {

            ## Read value
            $draw = request('draw');

            $start = request('start');

            $rowperpage = request('length'); // Rows display per page

            $columnIndex = request('order.0.column'); // Column index

            //$columnName = request('columns')[$columnIndex]['data']; // Column name

            $columnSortOrder = request('order.0.dir'); // asc or desc

            $searchValue = request('search.value'); // Search value

            $q = request('q');
            $staffRole = request('role');

            $adminTbl = Admin::where('id', '!=', 1);

            ## Total number of records without filtering
            $totalRecords = $adminTbl->count();

            ## Total number of record with filtering
            $totalRecordwithFilter = $adminTbl->searchmembers($q)->staffrole($staffRole)->count();

            ## Fetch records
            $staffmembers = $adminTbl->searchmembers($q)->staffrole($staffRole)->offset($start)
                            ->limit($rowperpage)
                            //->orderBy($columnName, $columnSortOrder)
                            ->latest()
                            ->get(['id', 'user_id', 'name', 'email', 'phone_no', 'is_role']);

            $data = array();

            $i = $start;

            $deleteConfirmationMsg = "'Are you sure you want to delete?'";

            foreach($staffmembers as $key => $row) {

                $i++;

                /*$actions = Helper::getButtons([
                                ['key' => 'Edit', 'link' => route('admin.staffmembers.edit', $row->id)],
                                ['key' => 'Delete', 'link' => route('admin.staffmembers.delete', [$row->id])]
                            ]);*/

                /*$actions = '<span class="dropdown">
                                <a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="false">
                                  <i class="la la-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-32px, 27px, 0px);">
                                    <a class="dropdown-item" href="'.route('admin.staffmembers.edit', [$row->id]).'"><i class="la la-edit"></i> Edit</a>
                                    <a class="dropdown-item" href="'.route('admin.staffmembers.delete', $row->id).'" onclick="return confirm('.$deleteConfirmationMsg.')" ><i class="la la-trash"></i> Delete</a>
                                </div>
                            </span>';*/
                //Check authorization
                $editActionsDisabled = '';
                if($this->checkPermission(Auth::guard('admin')->user()->is_role, 'staff-members', 'is_edit'))
                {
                    $editActionsDisabled .= '<a class="dropdown-item" href="'.route('admin.staffmembers.edit', [$row->id]).'"><i class="la la-edit"></i> Edit</a>';
                }
                if($this->checkPermission(Auth::guard('admin')->user()->is_role, 'staff-members', 'is_delete'))
                {
                    $editActionsDisabled .= '<a class="dropdown-item" href="'.route('admin.staffmembers.delete', $row->id).'" onclick="return confirm('.$deleteConfirmationMsg.')" ><i class="la la-trash"></i> Delete</a>';
                }
                $actions = '';
                if(!empty($editActionsDisabled)) {
                    $actions = '<span class="dropdown">
                                    <a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="false">
                                      <i class="la la-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-32px, 27px, 0px);">'.$editActionsDisabled.'</div>
                                </span>';
                }

                $profileDetail = '<div class="m-card-user m-card-user--sm">
                                    <div class="m-card-user__pic">
                                        <img src="'.Profile::admin_avatar($row->id).'" class="m--img-rounded m--marginless" alt="'.$row->name.'">
                                    </div>
                                    <div class="m-card-user__details">
                                        <span class="m-card-user__name">'.$row->name.'</span>
                                        <a href="'.route('admin.staffmembers.show', $row->id).'" class="m-card-user__email m-link"><i class="fa fa-globe globe_icon"></i>&nbsp;&nbsp;'.$row->user_id.'</a>
                                    </div>
                                </div>';

                //$data[$key]['id'] = $i;
                //$data[$key]['name'] = '<a href="'.route('admin.staffmembers.show', $row->id).'" class="_adetail">'.$row->name.'</a>';
                //$phoneNo = '<i class="la la-phone-square"></i> '.$row->phone_no;
                $phoneNo = '<ul style="list-style-type: none; margin: 0; padding: 0;">
                                <li><i class="fa fa-phone phone_icon"></i>&nbsp;&nbsp;'.$row->phone_no.'</li>
                                <li><i class="fa fa-envelope email_icon"></i>&nbsp;&nbsp;'.$row->email.'</li>
                            </ul>';

                $data[$key]['name'] = $profileDetail;
                //$data[$key]['email'] = $row->email;
                $data[$key]['phone_no'] = $phoneNo;
                $data[$key]['role'] = $row->role->name;
                $data[$key]['action'] = $actions;
            }

            ## Response
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordwithFilter,
                "aaData" => $data
            );

            echo json_encode($response);

            exit();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'staff-members', 'is_add'))
        {
            //return redirect()->route('admin.dashboard')->with('warning', 'You are not authorised to access that location.');
            //return redirect()->route('admin.dashboard');
            return abort(403);
        }

        $this->setPageTitle('Manage Staff Members', 'Create Staff Members');

        $renderButtons = $this->addFormButtons(route('admin.staffmembers.index'));

        $backButtons = $this->backButtons(route('admin.staffmembers.index'));

        $roles = Role::all();

        return view('admin.staffmembers.create', compact('renderButtons', 'backButtons', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMember $request)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'staff-members', 'is_add'))
        {
            return abort(403);
        }

        try {

                $this->save($this->tbl, $request);

                return redirect()->route('admin.staffmembers.index')->with('success', 'New staff member added successfully.');

        } catch (Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
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
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'staff-members', 'is_edit'))
        {
            return abort(403);
        }

        $staff = $this->staffFindById($id);

        $this->setPageTitle('Manage Staff Members', 'Edit Staff Member');

        $renderButtons = $this->editFormButtons(route('admin.staffmembers.index'));

        $backButtons = $this->backButtons(route('admin.staffmembers.index'));

        $roles = Role::all();

        return view('admin.staffmembers.edit', compact('staff', 'renderButtons', 'backButtons', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMember $request, $id)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'staff-members', 'is_edit'))
        {
            return abort(403);
        }

        $admin = $this->staffFindById($id);

        $admin = Admin::findOrFail($id);

        try {

            $this->save($admin, $request);

            return redirect()->route('admin.staffmembers.index')->with('success', 'Staff member detail updated successfully.');

        } catch (Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
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
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'staff-members', 'is_delete'))
        {
            return redirect()->route('admin.dashboard')->with('warning', 'You are not authorised for this action.');
        }

        $admin = $this->staffFindById($id);
        $admin->delete();
        return redirect()->route('admin.staffmembers.index')->with('success', 'Staff member deleted successfully.');
    }


    protected function save(Admin $admin, Request $request)
    {
        $data['name'] = $request->name ? : NULL;
        $data['email'] = $request->email ? : NULL;
        if($request->password!='') {
        $data['password'] = Hash::make($request->password);
        }
        $data['phone_no'] = $request->phone_no ? : NULL;
        $data['is_role'] = $request->role ? : false; //Super admin (0)

        if($admin->id) {
            $admin->update($data);
        }else{

            $adminUserId = Admin::orderBy('user_id', 'desc')->first(['user_id']);

            if(empty($adminUserId->user_id)) {

                $data['user_id'] = 1001;

            }else{

                $data['user_id'] = $adminUserId->user_id+1;
            }
            return $admin->create($data);
        }
    }


    /**
     *
     * Staff member find by id.
     */
    public function staffFindById($id)
    {
        $member =  $this->tbl::where('id', '!=', 1)->where('id', $id)->first();

        if($member) {
            return $member;
        }

        return abort(404);

    }
}
