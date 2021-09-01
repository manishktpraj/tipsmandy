<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Traits\CurlTrait;
use Carbon\Carbon;
use Exception;

class RapidapiController extends Controller
{
    use CurlTrait;

    const XRAPIDAPIHOST = 'apidojo-yahoo-finance-v1.p.rapidapi.com';
        
    //const XRAPIDAPIKEY = '498b014f77msh41ff55343856325p171f45jsn73c19120e10b';
	
	const XRAPIDAPIKEY = '91a67b3856mshb8f78c73feb951ep1027cdjsn584a8058b4c2';

    public function getChartData(Request $request)
    {
        if(request()->ajax()) {

            $output = array('success' => '', 'error' => '', 'data' => '', 'regularMarketPrice' => '');

            try {

                $symbol = $request->symbol;
            
                $symbolVal = Str::upper($request->symbolVal);

                $symbolData = $symbolVal.'.'.$symbol;

                $period1Date = Carbon::now()->format('Y-m-d').' 09:30:00';
                
                $period2Date = Carbon::now()->format('Y-m-d').' 17:30:00'; // 05:30:00 PM
            
                $period1 = strtotime($period1Date);
            
                $period2 = strtotime($period2Date);

                $curl = curl_init();

                curl_setopt_array($curl, [
                    //CURLOPT_URL => "https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-chart?interval=5m&symbol=TCS.NS&range=1d&region=IN",
                    CURLOPT_URL => "https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-chart?interval=5m&symbol=".$symbolData."&range=1d&region=IN",
                    //CURLOPT_URL => "https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-chart?interval=5m&symbol=AMRN&range=1d&region=US",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => [
                        "x-rapidapi-host: apidojo-yahoo-finance-v1.p.rapidapi.com",
                        "x-rapidapi-key: 91a67b3856mshb8f78c73feb951ep1027cdjsn584a8058b4c2"
                    ],
                ]);

                $response = curl_exec($curl);

                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {

                    $output['error'] = "cURL Error #:" . $err;

                } else {

                    $obj = json_decode($response);
                    
                    if(!empty($obj->chart->error->description)) {
                        
                        $output['error'] = $obj->chart->error->description;
                    
                    }elseif(!empty($obj->chart->result[0]->meta->regularMarketPrice)) {

                        $output['regularMarketPrice'] = $obj->chart->result[0]->meta->regularMarketPrice;
                        $output['success'] = 'Data found successfully.';

                    }else{

                        $output['error'] = 'Api not provide data. please try again!';
                    }

                }

            } catch (Exception $e) {
                
                //return redirect()->back()->with('error', $e->getMessage());

                $output['error'] = $e->getMessage();

            }

            return $this->outputJSON($output);
        
        }else{
            
            $period1Date = Carbon::now()->format('Y-m-d').' 09:30:00';
            
            $period2Date = Carbon::now()->format('Y-m-d').' 17:30:00';
            
            $period1 = strtotime($period1Date);
            
            $period2 = strtotime($period2Date);

            $array['currentDate'] = $currentDate = strtotime(Carbon::now());
            $array['rapidapiHost'] = self::XRAPIDAPIHOST;
            $array['rapidapiKey'] = self::XRAPIDAPIKEY;
            //$array['rapidapiUrl'] = "https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-chart?interval=15m&symbol=TCS.NS&range=1d&region=IN&period2=".$period2."&period1=".$period1."";
            $array['rapidapiUrl'] = "https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-chart?interval=15m&symbol=TCS.NS&region=IN&period2=".$period2."&period1=".$period1."";
            

            $curl = $this->rapidapiCurl($array);

            $response = curl_exec($curl);
            
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {

                echo "cURL Error #:" . $err;

            } else {

                echo '<pre>';
                print_r($response);
                exit();
                $obj = json_decode($response);
                if(!empty($obj->chart->error->description)) {
                    echo $obj->chart->error->description;
                }
                echo '<pre>';
                print_r($obj->chart->result[0]->meta->regularMarketPrice);
            }

            exit();
            // PHP program to demonstrate the strtotime() 
            // function when the english text is "now"
              
            // prints current time in second 
            // since now means current 
            //echo strtotime("now"), "\n"; 
              
            // prints the current time in date format 
            //echo date("Y/m/d H:i:s", '1571590800')."\n";
            //echo(strtotime("+5 hours") . "<br>");
            //echo date("Y/m/d H:i:s", strtotime("-1 hours"))."<br>";
            //echo date("Y/m/d H:i:s", '1571590800')."<br>";
            //echo date("Y/m/d H:i:s", '493578000')."<br>";

            //$str_time = now();

            //echo strtotime($str_time);
            
            //echo $currentDate = Carbon::now()."<br>";
            //echo $currentDate = Carbon::now()->format('Y-m-d');
            
            $period1Date = Carbon::now()->format('Y-m-d').' 09:30:00';
            $period2Date = Carbon::now()->format('Y-m-d').' 17:30:00';
            //exit();

            
            //$period1 = strtotime("-1 hours");
            $period1 = strtotime($period1Date);
            
            $period2 = strtotime($period2Date);

            $curl = curl_init();

            curl_setopt_array($curl, [
                //CURLOPT_URL => "https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-chart?interval=5m&symbol=TCS.NS&range=1d&region=IN",
                //CURLOPT_URL => "https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-chart?interval=5m&symbol=TCS.NS&region=IN&period2=1571590800&period1=493578000",
                //CURLOPT_URL => "https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-chart?interval=15m&symbol=TCS.NS&range=1d&region=IN&period2=1571590800&period1=493578000",
                CURLOPT_URL => "https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-chart?interval=15m&symbol=TCS.NS&range=1d&region=IN&period2=".$period2."&period1=".$period1."",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "x-rapidapi-host: apidojo-yahoo-finance-v1.p.rapidapi.com",
                    "x-rapidapi-key: 91a67b3856mshb8f78c73feb951ep1027cdjsn584a8058b4c2"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {

                echo "cURL Error #:" . $err;

            } else {

                echo '<pre>';
                print_r($response);
                exit();
                $obj = json_decode($response);
                if(!empty($obj->chart->error->description)) {
                    echo $obj->chart->error->description;
                }
                echo '<pre>';
                print_r($obj->chart->result[0]->meta->regularMarketPrice);
            }
        }
    }

    public function getHistoricalData()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://apidojo-1571590800yahoo-finance-v1.p.rapidapi.com/stock/v3/get-historical-data?symbol=TCS.NS&region=IN",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: apidojo-yahoo-finance-v1.p.rapidapi.com",
                "x-rapidapi-key: 91a67b3856mshb8f78c73feb951ep1027cdjsn584a8058b4c2"
                //"x-rapidapi-key: a50b9e6ecamsh070c497ff78ece8p144f55jsnacc974fef742"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            
            echo '<pre>';
            print_r($response);

            //return response()->json($response);
        }
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-timeseries?symbol=TCS&period2=1571590800&period1=493578000&region=NS",
            //CURLOPT_URL => "https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-timeseries?symbol=IBM&period2=1571590800&period1=493578000&region=US",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: apidojo-yahoo-finance-v1.p.rapidapi.com",
                "x-rapidapi-key: 91a67b3856mshb8f78c73feb951ep1027cdjsn584a8058b4c2"
                //"x-rapidapi-key: a50b9e6ecamsh070c497ff78ece8p144f55jsnacc974fef742"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            
            echo '<pre>';
            print_r($response);

            //return response()->json($response);
        }
    }


    public function getOptionsData(Request $request)
    {
        if(request()->ajax()) {

            $output = array('success' => '', 'error' => '', 'data' => '', 'regularMarketPrice' => '');

            try {

                $symbol = $request->symbol;
            
                $symbolVal = Str::upper($request->symbolVal);

                $symbolData = $symbolVal.'.'.$symbol;

                $period1Date = Carbon::now()->format('Y-m-d').' 09:30:00';
                
                $period2Date = Carbon::now()->format('Y-m-d').' 17:30:00'; // 05:30:00 PM
            
                $period1 = strtotime($period1Date);
            
                $period2 = strtotime($period2Date);

                $curl = curl_init();

                curl_setopt_array($curl, [
                    //CURLOPT_URL => "https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-chart?interval=5m&symbol=TCS.NS&range=1d&region=IN",
                    //CURLOPT_URL => "https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-chart?interval=5m&symbol=".$symbolData."&range=1d&region=IN",
                    CURLOPT_URL => "https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-chart?interval=15m&symbol=".$symbolData."&range=1d&region=IN&period2=".$period2."&period1=".$period1."",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => [
                        "x-rapidapi-host: self::XRAPIDAPIHOST",
                        "x-rapidapi-key: self::XRAPIDAPIHOST"
                    ],
                ]);

                $response = curl_exec($curl);

                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {

                    $output['error'] = "cURL Error #:" . $err;

                } else {

                    $obj = json_decode($response);
                    
                    if(!empty($obj->chart->error->description)) {
                        
                        $output['error'] = $obj->chart->error->description;
                    
                    }elseif(!empty($obj->chart->result[0]->meta->regularMarketPrice)) {

                        $output['regularMarketPrice'] = $obj->chart->result[0]->meta->regularMarketPrice;
                        $output['success'] = 'Data found successfully.';

                    }else{

                        $output['error'] = 'Api not provide data. please try again!';
                    }

                }

            } catch (Exception $e) {
                
                //return redirect()->back()->with('error', $e->getMessage());

                $output['error'] = $e->getMessage();

            }

            return $this->outputJSON($output);
        
        }else{

            
            //$currentDate = strtotime(Carbon::now());
            
            $array['currentDate'] = $currentDate = strtotime(Carbon::now());
            $array['rapidapiHost'] = self::XRAPIDAPIHOST;
            $array['rapidapiKey'] = self::XRAPIDAPIKEY;
            $array['rapidapiUrl'] = "https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-options?symbol=NTPC.NS&date=".$currentDate."&region=IN";
            

            $curl = $this->rapidapiCurl($array);

            $response = curl_exec($curl);
            
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {

                echo "cURL Error #:" . $err;

            } else {

                echo '<pre>';
                print_r($response);
                
            }
        }
    }

    public function liveMetalPrices(Request $request)
    {
        
            
        $array['currentDate'] = $currentDate = strtotime(Carbon::now());
        $array['rapidapiHost'] = "live-metal-prices.p.rapidapi.com";
        $array['rapidapiKey'] = self::XRAPIDAPIKEY;
        $array['rapidapiUrl'] = "https://live-metal-prices.p.rapidapi.com/v1/latest/XAU,XAG,PA/INR/gram";        

        $curl = $this->rapidapiCurl($array);

        $response = curl_exec($curl);
        
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {

            echo "cURL Error #:" . $err;

        } else {

            echo '<pre>';
            print_r($response);
            
        }
        
    }
}
