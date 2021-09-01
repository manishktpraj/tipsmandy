<?php

namespace App\Http\Controllers\Admin\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Traits\Admin\FormButtons;
use App\Traits\Admin\AuthorizationsTrait;
use App\Models\Support;
use App\Models\Notification;
use App\Models\UserPlan;
use App\Models\UserDevice;
use App\Traits\FcmTrait;
use App\User;
use Exception;
use Helper;

class NotificationsController extends Controller
{	
	use FormButtons, AuthorizationsTrait, FcmTrait;
	
	protected $tbl;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');

        $this->tbl = new User;
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->setPageTitle('Manage Notifications', 'Send Notifications');
		
		return view('admin.notifications.send-notification');
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
		if($request->notification_type==1) {
			
			$users = UserDevice::select("*")
							//->where('user_id', 1)
                            ->orderBy('id', 'desc')
                            ->get()
                            ->unique('user_id');
			
			//$users = DB::table('user_devices')->where('user_id', 1)->groupBy('user_id')->groupBy('device_token')->get();
			
		}elseif($request->notification_type==2) {
			$users = DB::table('users')
				->join('user_devices', 'users.id', '=', 'user_devices.user_id')
				->select('users.*', 'user_devices.device_token')
				->whereNotNull('users.plan_id')
				->groupBy('user_devices.user_id')
				->get();		
				
				
		}elseif($request->notification_type==3) {
			/*$users = DB::table('users')
				->join('user_devices', 'users.id', '=', 'user_devices.user_id')
				->select('users.*', 'user_devices.device_token')
				->whereNull('users.plan_id')
				->get();*/
				
			$users = DB::table('user_devices')->whereNull('user_id')->groupBy('device_token')->get();
			
			
			
		}else{
			$users = [];
		}
			
		//dd($users);
		if(count($users)) {
			foreach($users as $user)
			{
				$this->sendPushNotification($user->device_token, 'Tips Mandi', $request->notification, 'Notification');
				
				if($request->notification_type==1) {
					$user_id = $user->user_id;
				}elseif($request->notification_type==2) {
					$user_id = $user->id;
				}elseif($request->notification_type==3) {
					$user_id = $user->user_id;
				}
				
				DB::table('notifications')->insert([
					'user_id' => $user_id,
					'content' => $request->notification,
				]);
			}
		}
		
		return redirect()->back()->with('success', 'Notification send successfully.');
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
}
