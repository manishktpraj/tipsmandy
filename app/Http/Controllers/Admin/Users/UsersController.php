<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Traits\Admin\FormButtons;
use App\Traits\Admin\AuthorizationsTrait;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Admin;
use App\User;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\UserPlan;
use App\Models\MarketStockData;
use Carbon\Carbon;
use Exception;
use SimpleXLSX;
use Helper;
use Profile;

class UsersController extends Controller
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
        $this->tbl = new User;
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-members', 'is_read'))
        {
            return abort(403);
        }


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

            $userTbl = new User;

            ## Total number of records without filtering
            $totalRecords = $userTbl->count();

            ## Total number of record with filtering
            $totalRecordwithFilter = $userTbl->count();

            ## Fetch records
            $members = $userTbl->offset($start)
                            ->limit($rowperpage)
                            ->latest()
                            ->get(['id', 'user_id', 'plan_id', 'name', 'email', 'phone_no','gender']);

            $data = array();

            $i = $start;

            foreach($members as $key => $row) {

                $i++;

				$gender = $row->gender == 1 ? 'Already have' : 'Yet to have';
                $profileDetail = '<div class="m-card-user m-card-user--sm">
                                    <div class="m-card-user__pic">
                                        <img src="'.Profile::picture($row->id).'" class="m--img-rounded m--marginless" alt="'.$row->name.'">
                                    </div>
                                    <div class="m-card-user__details">
                                        <span class="m-card-user__name">'.$row->name.'</span>
                                        <a href="javascript:;" class="m-card-user__email m-link"><i class="fa fa-globe globe_icon"></i>&nbsp;&nbsp;'.$row->user_id.'</a> <img alt="" src="https://stockedge.itworkshop.in/public/images/calculations.png" width="16"> 
										
								'.$gender.'
										
                                    </div>
                                </div>';

                $phoneNo = '<ul style="list-style-type: none; margin: 0; padding: 0;">
                                <li><i class="fa fa-phone phone_icon"></i>&nbsp;&nbsp;'.$row->phone_no.'</li>
                                <li><i class="fa fa-envelope email_icon"></i>&nbsp;&nbsp;'.$row->email.'</li>
                            </ul>';

                $plan_expiry_date = '';
                if(!empty($row->userplanDetail->plan_expiry_date)) {
                    $plan_expiry_date = '<i class="fa fa-toolbox toolbox_icon"></i>&nbsp;&nbsp;'.$row->userplanDetail->plan_expiry_date;
                }
                //$data[$key]['id'] = $i;
                $data[$key]['name'] = $profileDetail;
                //$data[$key]['email'] = $row->email;
                $data[$key]['phone_no'] = $phoneNo;
                $data[$key]['plan'] = $row->plan->name;
                $data[$key]['plan_expiry_date'] = $plan_expiry_date;
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

        $this->setPageTitle('Manage Members', 'Manage Members');

        $renderButtons = $this->addButtons(route('admin.staffmembers.create'), 'staff-members', 'Add Staff Member');

        return view('admin.users.index', compact('renderButtons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        $this->setPageTitle('Import Members', 'Import Members');

        $backButtons = $this->backButtons(route('admin.users.index'));

        /*$email = 'koss.dasia@exdddample.com';
        $phone_no = '9696554546';
        $checkMember = User::where(function($query) use ($email, $phone_no) {
                                        $query->orWhere('email', $email)
                                                ->orWhere('phone_no', $phone_no);
                                    })->first();
        dd($checkMember);*/

        return view('admin.users.import', compact('backButtons'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function memberImport(Request $request)
    {
        $request->validate([
            'member_excel_file' => 'required|mimes:xlsx',
        ]);

        //Check request file exit or not
        if($request->hasFile('member_excel_file')) {

            $file = $request->file('member_excel_file')->getRealPath();

            if ( $xlsx = SimpleXLSX::parse($file)) {

                // Produce array keys from the array values of 1st array element
                $header_values = $rows = [];

                foreach ( $xlsx->rows() as $k => $r ) {

                    if ( $k === 0 ) {
                        $header_values = $r;
                        continue;
                    }

                    $rows[] = array_combine( $header_values, $r );
                }

            }

            $count = 0;

            foreach ($rows as $key => $row) {

                $count++;

                if ($count == 1) { continue; }

                if(!empty($row['Name']) && !empty($row['Email']) && !empty($row['Phone No']) && !empty($row['Plan']) && !empty($row['Plan Duration'])) {

                    $name = $row['Name'];
                    $email = $row['Email'];
                    $phoneNo = $row['Phone No'];
                    $plan = $row['Plan'];
                    $planDuration = $row['Plan Duration'];

                    $checkMember = User::where(function($query) use ($email, $phoneNo) {
                                                    $query->orWhere('email', $email)->orWhere('phone_no', $phoneNo);
                                                })->first();

                    if(empty($checkMember)) {

                        $data['user_id'] = $this->generateIdNumber();
                        $data['name'] = $name;
                        $data['email'] = $email;
                        $data['email_verified_at'] = now();
                        //$data['password'] = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password
                        $data['password'] = Hash::make('password'); // password
                        $data['remember_token'] = Str::random(10);
                        $data['phone_no'] = $phoneNo;

                        $planId = '';

                        //Get plan detail
                        $planDetail = Plan::where('name', $plan)->first(['id', 'name']);

                        if($planDetail) {

                            $data['plan_id'] = $planId = $planDetail->id;

                        }

                        $create = User::create($data);

                        if($create) {

                            $planDurationDetail = PlanPrice::where('plan_id', $planId)->where('plan_month', $planDuration)->first();

                            if($planDurationDetail) {

                                $userPlanArray['user_id'] = $create->id;
                                $userPlanArray['plan_id'] = $planId;
                                $userPlanArray['plan_duration'] = $plan_duration = $planDurationDetail->plan_month;
                                $userPlanArray['price'] = $planDurationDetail->price;

                                $currentDateTime = Carbon::now();

                                $newDateTime = Carbon::now()->addMonths($plan_duration);

                                $userPlanArray['plan_expiry_date'] = Carbon::parse($newDateTime)->format('Y-m-d');

                                UserPlan::create($userPlanArray);
                            }
                        }
                    }
                }
            }

            return redirect()->route('admin.users.index')->with('success', 'Members imported successfully.');

        }else{
            return redirect()->back()->with('success', 'Members imported successfully.');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function stockDataImport(Request $request)
    {
        $request->validate([
            'member_excel_file' => 'required|mimes:xlsx',
        ]);

        //Check request file exit or not
        if($request->hasFile('member_excel_file')) {

            $file = $request->file('member_excel_file')->getRealPath();

            if ( $xlsx = SimpleXLSX::parse($file)) {

                // Produce array keys from the array values of 1st array element
                $header_values = $rows = [];

                foreach ( $xlsx->rows() as $k => $r ) {

                    if ( $k === 0 ) {
                        $header_values = $r;
                        continue;
                    }

                    $rows[] = array_combine( $header_values, $r );
                }

            }

            $count = 0;

            foreach ($rows as $key => $row) {

                $count++;

                if ($count == 1) { continue; }

                //if(!empty($row['Symbol']) && !empty($row['Company Name']) && !empty($row['Exchange'])) {
                if(!empty($row['Symbol']) && !empty($row['Company Name'])) {

                    $symbol = trim($row['Symbol']);
                    $name = trim($row['Company Name']);
                    //$exchange = $row['Exchange'];
                    $exchange = 'BSE';

                    $data['symbol'] = $symbol;
                    $data['name'] = $name;
                    $data['exchange'] = $exchange;
                    MarketStockData::create($data);
                }
            }

            return redirect()->route('admin.users.index')->with('success', 'Members imported successfully.');

        }else{
            return redirect()->back()->with('success', 'Members imported successfully.');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function marketstockdataImport(Request $request)
    {
        $request->validate([
            'member_excel_file' => 'required|mimes:xlsx',
        ]);

        //Check request file exit or not
        if($request->hasFile('member_excel_file')) {

            $file = $request->file('member_excel_file')->getRealPath();

            if ( $xlsx = SimpleXLSX::parse($file)) {

                // Produce array keys from the array values of 1st array element
                $header_values = $rows = [];

                foreach ( $xlsx->rows() as $k => $r ) {

                    if ( $k === 0 ) {
                        $header_values = $r;
                        continue;
                    }

                    $rows[] = array_combine( $header_values, $r );
                }

            }

            $count = 0;

            foreach ($rows as $key => $row) {

                $count++;

                if ($count == 1) { continue; }

                //if(!empty($row['Symbol']) && !empty($row['Company Name']) && !empty($row['Exchange'])) {
                if(!empty($row['Security Code']) && !empty($row['Symbol']) && !empty($row['Company Name'])) {

                    $security_code = trim($row['Security Code']);
                    $symbol = trim($row['Symbol']);
                    $name = trim($row['Company Name']);
                    //$exchange = $row['Exchange'];
                    $exchange = 'BSE';

                    $data['security_code'] = $security_code;
                    $data['symbol'] = $symbol;
                    $data['name'] = $name;
                    $data['exchange'] = $exchange;
                    MarketStockData::create($data);
                }
            }

            return redirect()->route('admin.users.index')->with('success', 'Members imported successfully.');

        }else{
            return redirect()->back()->with('success', 'Members imported successfully.');
        }
    }

    /**
     * Generate product bar code
     *
     * @return \string
     */
    private function generateIdNumber()
    {

        do{

            $user = User::orderBy('user_id', 'desc')->first(['user_id']);

            if(empty($user->user_id)) {

                $unique_code = 1001;

            }else{

                $unique_code = $user->user_id+1;
            }

        }while (!empty(User::whereUserId($unique_code)->first()));

        return $unique_code;

    }

    public function memberExport()
    {
        // Excel file name for download
        $fileName = "members_export_data-" . date('Ymd') . ".xlsx";

        // Column names
        $fields = array('#', 'User ID', 'Name', 'Email', 'Phone No', 'Plan', 'Plan Expiry Date');

        // Display column names as first row
        $excelData = implode("\t", array_values($fields)) . "\n";

        // Get records from the database
        $members = User::latest()->get(['id', 'user_id', 'plan_id', 'name', 'email', 'phone_no']);

        // Output each row of the data
        $i=0;
        foreach ($members as $member) {
            $i++;
            $rowData = array($i, $member->user_id, $member->name, $member->email, $member->phone_no, '1', '123');
            //array_walk($rowData, self::filterData());
            $excelData .= implode("\t", array_values($rowData)) . "\n";
        }

        // Headers for download
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Content-Type: application/vnd.ms-excel");

        // Render excel data
        echo $excelData;

        exit;

    }

    function filterData(&$str=null)
    {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    }
}
