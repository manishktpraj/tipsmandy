<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\MutualFund;
use App\Traits\CurlTrait;
use Exception;

class MutalfundsapiController extends Controller
{
    use CurlTrait;

    const XRAPIDAPIHOST = 'apidojo-yahoo-finance-v1.p.rapidapi.com';

    //const XRAPIDAPIKEY = '91a67b3856mshb8f78c73feb951ep1027cdjsn584a8058b4c2';

    const XRAPIDAPIKEY = '498b014f77msh41ff55343856325p171f45jsn73c19120e10b';

    //const XRAPIDAPIKEY = '4e22195b30msh9d0122f52b989cdp1a96f3jsn8f2fbae06089';

    protected $rapidapitestkey;

    protected $tblMutualFund;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->tblMutualFund = new MutualFund;
        $this->rapidapitestkey = config('settings.rapidapi_test_key');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://latest-mutual-fund-nav.p.rapidapi.com/fetchAllSchemeNames",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: latest-mutual-fund-nav.p.rapidapi.com",
                "x-rapidapi-key: 4e22195b30msh9d0122f52b989cdp1a96f3jsn8f2fbae06089"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {

            $obj = json_decode($response);
            //echo '<pre>';
            //print_r($obj);
            //exit();
            //foreach($obj as $key => $title)
            //{
                //DB::table('mutual_funds')->insert(['title' => $title]);
            //}

        }*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mfapi($scheme_code)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.mfapi.in/mf/".$scheme_code,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            //echo "cURL Error #:" . $err;
        } else {
            if(isset($response)) {
                return $response;
            }
        }
    }

    public function search(Request $request)
    {

        $search = $request->search;

        if($search == ''){
            $mutualfunds = MutualFund::orderby('title','asc')->select('id','title')->limit(5)->get();
        }else{
            $mutualfunds = MutualFund::orderby('title','asc')->select('id','title')->where('title', 'like', '%' .$search . '%')->limit(5)->get();
        }

        $response = array();

        foreach($mutualfunds as $mutualfund){
            $response[] = array("value"=>$mutualfund->id,"label"=>$mutualfund->title);
        }

        return response()->json($response);
    }

    public function latestmutualfundDetail(Request $request)
    {

        //if(request()->ajax() || $request->search) {

            $output = array(
                            'success' => '',
                            'error' => '',
                            'scheme_code' => '',
                            'isin_div_payout_isin_growth' => '',
                            'isin_div_reinvestment' => '',
                            'scheme_name' => '',
                            'net_asset_value' => '',
                            'date' => '',
                            'scheme_type' => '',
                            'scheme_category' => '',
                            'mutual_fund_family' => '',
                            'mf_api' => '',
                        );

            if(!empty($request->scheme_name)) {
                $q =  rawurlencode($request->scheme_name);
            }else{
                $q =  rawurlencode('Kotak Asset Allocator Fund Direct Growth - Direct');
            }

            $array['rapidapiHost'] = "latest-mutual-fund-nav.p.rapidapi.com";
            $array['rapidapiKey'] = self::XRAPIDAPIKEY;
            $array['rapidapiUrl'] = "https://latest-mutual-fund-nav.p.rapidapi.com/fetchLatestNAV?SchemeName=".$q;

            $curl = $this->rapidapiCurl($array);

            $response = curl_exec($curl);

            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {

                $output['error'] = "cURL Error #:" . $err;

            } else {

                $obj = json_decode($response, true);

                if(isset($obj['message'])) {
                    $output['error'] = $obj['message'];
                }else{
                    $output['scheme_code'] =  $obj[0]['Scheme Code'] ?? '';
                    $output['isin_div_payout_isin_growth'] =  $obj[0]['ISIN Div Payout/ISIN Growth'] ?? '';
                    //$output['isin_div_reinvestment'] =  $obj[0]['ISIN Div Reinvestment'] ?? '';
                    $output['isin_div_reinvestment'] =  $obj[0]['ISIN Div Reinvestment'] ?? '';
                    $output['scheme_name'] =  $obj[0]['Scheme Name'] ?? '';
                    $output['net_asset_value'] =  $obj[0]['Net Asset Value'] ?? '';
                    $output['date'] =  $obj[0]['Date'] ?? '';
                    $output['scheme_type'] =  $obj[0]['Scheme Type'] ?? '';
                    $output['scheme_category'] =  $obj[0]['Scheme Category'] ?? '';
                    $output['mutual_fund_family'] =  $obj[0]['Mutual Fund Family'] ?? '';
                    $output['success'] =  'Data search successfully.';
                    if(!empty($obj[0]['Scheme Code'])){
                        $output['mf_api'] =  $this->mfapi($obj[0]['Scheme Code']);
                    }
                }
            }

            return $this->outputJSON($output);
        //}

        //return $this->invalidajaxRequest();
    }
}
