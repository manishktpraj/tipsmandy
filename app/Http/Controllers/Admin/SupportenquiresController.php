<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Traits\Admin\AuthorizationsTrait;
use App\Models\Support;
use App\Models\PartnerWithUs;
use Exception;
use Helper;

class SupportenquiresController extends Controller
{

    protected $tbl, $tblPartnerWithUs;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');

        $this->tbl = new Support;
		
		$this->tblPartnerWithUs = new PartnerWithUs;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		

        $this->setPageTitle('Manage Support Inquiry', 'Manage Support Inquiry');

        $supports = $this->tbl::orderBy('id', 'desc')->get(['id', 'ticket_id', 'name', 'email', 'phone', 'message']);

        return view('admin.support-inquiry.index', compact('supports'));
    }
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function partnerWithUs()
    {
		

        $this->setPageTitle('Manage Partner with us Inquiry', 'Manage Partner with us Inquiry');

        $supports = $this->tblPartnerWithUs::orderBy('id', 'desc')->get(['id', 'name', 'email', 'phone', 'message']);

        return view('admin.support-inquiry.partner-with-us', compact('supports'));
    }
	
	
public function referral_userlist()
{

$this->setPageTitle('Referral Userlist', 'Referral Userlist');
	
	$supports = DB::table('referral_user')
                    ->join('users', 'referral_user.referral_userid', '=', 'users.id')
                    ->select('referral_user.*', 'users.name','users.email','users.user_id as user')
		       	->whereNull('referral_user.payment_status')
		            ->groupby('referral_user.referral_userid')
                    ->get();
	
		$supportscounts = DB::table('referral_user')
                    ->join('users', 'referral_user.referral_userid', '=', 'users.id')
                    ->select('referral_user.*', 'users.name','users.email','users.user_id as user')
		       	->whereNull('referral_user.payment_status')
                    ->get();
	


return view('admin.support-inquiry.referral_code', compact('supports','supportscounts'));
}
	
	
	public function referral_userdetails($id)
{

$this->setPageTitle('Referral UserDetails', 'Referral UserDetails');
	
	 $supports = DB::table('referral_user')
                    ->join('users', 'referral_user.user_id', '=', 'users.id')
                    ->select('referral_user.*', 'users.name','users.email','users.user_id as user')
		->where('referral_user.referral_userid',$id)
                    ->get();
		

return view('admin.support-inquiry.referral_code_details', compact('supports'));
}
	
	
		public function payment_status($id)
{

$userdetails['payment_status'] = "Paid";
			$result= DB::table('referral_user')->where('referral_userid',$id)->whereNull('payment_status')->update($userdetails);
return redirect()->back()->with('success', 'Payment Status Successfully.');
}
	

	
			public function payment_status_usedcode($id)
{


$userdetails['payment_status_usedby'] = "Paid";
			$result= DB::table('referral_user')->where('user_id',$id)->whereNull('payment_status_usedby')->update($userdetails);
return redirect()->back()->with('success', 'Payment Status Successfully.');
}
	

}
