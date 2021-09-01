<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\MarketStockData;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class FmpcloudsController extends Controller
{

    public function marketStockData(Request $request)
    {

        $validator = Validator::make($request->only(['symbol']), [
            'symbol' => 'required|string'
        ]);

        if ($validator->fails()) {

            return $this->outputJSON(['message' => $validator->errors()->first()], $this->notauthorized);
            exit();
        }

        $searchValue = $request->symbol;


       /* $checkMember = User::where(function($query) use ($email, $phone_no) {
                                        $query->orWhere('email', $email)
                                                ->orWhere('phone_no', $phone_no);
                                    })->first();*/

        $marketStockData = MarketStockData::where(function($query) use ($searchValue) {
                                        $query->orWhere('name','LIKE',"%{$searchValue}%")
                                                ->orWhere('name','LIKE',"%{$searchValue}%");
                                    })->get();

        $dataArray = [];


        $responseStatus = 202;
        $apiStatus = false;

        $message = 'Data not found.';

        if(count($marketStockData)) {$responseStatus = 200; $message = 'Data lists.'; $apiStatus = true;}


        foreach ($marketStockData as $key => $row) {

            $exchangeVal = $row->exchange == 'NSE' ? 'NS' : 'BO';

            $dataArray[$key]['symbol'] = $row->symbol.'.'.$exchangeVal;
            $dataArray[$key]['name'] = $row->name;
            $dataArray[$key]['exchange'] = $row->exchange;

        }

        $response = [
                'status' => $apiStatus,
                'message' => $message,
                'data' => $dataArray
            ];

        return response()->json($response, $responseStatus);

    }


    public function getautocompleteData(Request $request)
    {
        $validator = Validator::make($request->only(['q']), [
            'q' => 'required|string'
        ],[
            'q.required' => 'The query field is required.'
        ]);

        if ($validator->fails()) {

            return $this->outputJSON(['message' => $validator->errors()->first()], $this->notauthorized);
            exit();
        }

        $searchValue = $request->q;


       /* $checkMember = User::where(function($query) use ($email, $phone_no) {
                                        $query->orWhere('email', $email)
                                                ->orWhere('phone_no', $phone_no);
                                    })->first();*/

        /*$marketStockData = MarketStockData::where(function($query) use ($searchValue) {
                                        $query->orWhere('name','LIKE',"%{$searchValue}%")
                                                ->orWhere('name','LIKE',"%{$searchValue}%");
                                    })->get();*/
		//30-July-2021
        /*$marketStockData = MarketStockData::where('name','LIKE',"%{$searchValue}%")
                            ->groupBy('name')
                            ->groupBy('exchange')
                            ->get();*/
		
		$marketStockData = MarketStockData::where(function($query) use ($searchValue) {
							$query->orWhere('name','LIKE',"%{$searchValue}%")
									->orWhere('symbol','LIKE',"%{$searchValue}%");
						})
						->groupBy('name')
						->groupBy('exchange')
						->get();

        $dataArray = [];
        $responseStatus = 202;
        $apiStatus = false;

        $message = 'Data not found.';

        if(count($marketStockData)) {$responseStatus = 200; $message = 'Data lists.'; $apiStatus = true;}


        foreach ($marketStockData as $key => $row) {

            $exchangeVal = $row->exchange == 'NSE' ? 'NS' : 'BO';

            //$dataArray[$key]['symbol'] = $row->symbol.'.'.$exchangeVal;
			$dataArray[$key]['symbol'] = $row->symbol;
            $dataArray[$key]['security_code'] = $row->security_code ?? 0;
            $dataArray[$key]['name'] = $row->name;
            $dataArray[$key]['exchange'] = $row->exchange;

        }

        $response = [
                'status' => $apiStatus,
                'message' => $message,
                'data' => $dataArray
            ];

        return response()->json($response, $responseStatus);

        
        $validator = Validator::make($request->only(['q']), [ 
            'q' => 'required|string'
        ],[
            'q.required' => 'The query field is required.'
        ]);            

        if ($validator->fails()) {
            
            return $this->outputJSON(['message' => $validator->errors()->first()], $this->notauthorized);
            exit();            
        }
        

        //request data
        $searchValue = Str::upper($request->q);
        $exchangeValue =  'NSE';
        if($request->e) {
            $exchangeValue = Str::upper($request->e);
        }

        // From URL to get webpage contents.
        $url = "https://fmpcloud.io/api/v3/search?query=".$searchValue."&limit=10&exchange=".$exchangeValue."&apikey=81173a3b271b9a3f7327ef774a7f061f";

        // Initialize a CURL session.
        $ch = curl_init();

        // Return Page contents.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //grab URL and pass it to the variable.
        curl_setopt($ch, CURLOPT_URL, $url);

        $result = curl_exec($ch);

        $err = curl_error($ch);

        curl_close($ch);

        if ($err) {

            $response = [
                'status' => false,
                'message' => "cURL Error #:" . $err,
            ];

            return response()->json($response, 200);

            exit;

        }



        // From URL to get webpage contents.
        $url2 = "https://www.alphavantage.co/query?function=SYMBOL_SEARCH&keywords=TCS.BO&apikey=5L7LU6KQ0CS7U3KI";
        //$url2 = "https://www.alphavantage.co/query?function=SYMBOL_SEARCH&keywords=info&apikey=5L7LU6KQ0CS7U3KI";

        // Initialize a CURL session.
        $ch2 = curl_init();

        // Return Page contents.
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);

        //grab URL and pass it to the variable.
        curl_setopt($ch2, CURLOPT_URL, $url2);

        $result2 = curl_exec($ch2);

        $err2 = curl_error($ch2);

        curl_close($ch2);

        if ($err2) {

            $response = [
                'status' => false,
                'message' => "cURL Error #:" . $err2,
            ];

            return response()->json($response, 200);

            exit;

        }


        $obj2 = json_decode($result2);

        $dataArray2 = [];

        $symbol1 = '1. symbol';
        $name1 = '2. name';

        foreach($obj2->bestMatches as $key2 => $alphavantage)
        {
            $dataArray2[$key2]['symbol'] = $alphavantage->$symbol1;
            $dataArray2[$key2]['name'] = $alphavantage->$name1;
        }

        /*echo '<pre>';
        print_r($dataArray2);
        exit();*/

        $obj = json_decode($result);


        $dataArray = [];

        foreach($obj as $key => $fmpcloud)
        {
            $dataArray[$key]['name'] = $fmpcloud->name;
            $dataArray[$key]['symbol'] = $fmpcloud->symbol;
        }

        $marketArray = array_merge($dataArray, $dataArray2);

        $response = [
            'status' => true,
            'message' => 'Market data',
            'data' => $marketArray
        ];

        return response()->json($response, 200);
        echo '<pre>';
        print_r($dataArray);

    }



    public function marketstockdataBse(Request $request)
    {
        // replace the "demo" apikey below with your own key from https://www.alphavantage.co/support/#api-key
        /*$data = file_get_contents("https://www.alphavantage.co/query?function=TIME_SERIES_DAILY_ADJUSTED&symbol=RELIANCE.BSE&outputsize=full&apikey=5L7LU6KQ0CS7U3KI");
        $rows = explode("\n",$data);
        $s = array();
        foreach($rows as $row) {
            $s[] = str_getcsv($row);
            echo '<pre>';
            print_r($s);
        }*/

        $validator = Validator::make($request->only(['type', 'market', 'month', 'year']), [
            'type' => 'required|string|in:bse,nse',
            'market' => 'required|string',
            'month' => 'required|in:1,3,6,12,36,60',
            //'year' => 'nullable|in:1,3,5',
        ]);

        if ($validator->fails()) {

            return $this->outputJSON(['message' => $validator->errors()->first()], $this->notauthorized);
            exit();
        }

        $month = $request->month;

        $monthMarketData = 1;
        $year = $request->year;
        if($month==1) {
            $monthMarketData = Carbon::now()->subMonth()->format('Y-m-d');
        }elseif($month==3) {
            $monthMarketData = Carbon::now()->subMonths(3)->format('Y-m-d');
        }elseif($month==6) {
            $monthMarketData = Carbon::now()->subMonths(6)->format('Y-m-d');
        }elseif($month==12) {
            $monthMarketData = Carbon::now()->subYear()->format('Y-m-d');
        }elseif($month==36) {
            $monthMarketData = Carbon::now()->subYears(3)->format('Y-m-d');
        }elseif($month==60) {
            $monthMarketData = Carbon::now()->subYears(5)->format('Y-m-d');
        }
        //


        $symbol = $request->type;

        $security_code = ''; //$request->security_code;

        $market =  Str::upper($request->market);

        if($symbol=='bse') {

            $getMarketStockData = DB::table('market_stock_data')->where(['symbol' => $market, 'exchange' => 'BSE'])->first();

            if(empty($getMarketStockData)) {

                $response = [
                    'status' => false,
                    'message' => 'Data not found.',
                    'data' => []
                ];

                return response()->json($response, 200);

                exit();

            }else{

                $security_code = $getMarketStockData->security_code;
            }
        }

        if($symbol=='bse') {

            $apiUrl = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY_ADJUSTED&symbol=".$market.".BSE&outputsize=full&apikey=5L7LU6KQ0CS7U3KI";

        }else{
            //$apiUrl = "https://fmpcloud.io/api/v3/historical-price-full/RELIANCE.NS?from=2018-03-12&to=2019-03-12&apikey=demo";
            //$apiUrl = "https://fmpcloud.io/api/v3/historical-price-full/RELIANCE.NS?timeseries=30&apikey=81173a3b271b9a3f7327ef774a7f061f";
            //$apiUrl = "https://fmpcloud.io/api/v3/historical-price-full/RELIANCE.NS?timeseries=30&apikey=demo";
            $currentDate = Carbon::now()->format('d-m-Y');
            $endDate =  Carbon::parse($monthMarketData)->format('d-m-Y');

            $start_date = Carbon::createFromFormat('d-m-Y', $currentDate);
            $end_date = Carbon::createFromFormat('d-m-Y', $endDate);
            $different_days = $start_date->diffInDays($end_date);
            //dd($end_date);
            $apiUrl = "https://fmpcloud.io/api/v3/historical-price-full/".$market.".NS?timeseries=".$different_days."&apikey=869040c4435ee8d47cfacf2feb364ef9";
        }
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $apiUrl,
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

            $response = [
                'status' => false,
                'message' => "cURL Error #:" . $err,
            ];

            return response()->json($response, 200);

            exit;

        }

        $obj = json_decode($response, true);

        //dd($obj);

        if ($symbol=='bse' && isset($obj['Error Message'])) {

            $apiUrl = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY_ADJUSTED&symbol=".$security_code.".BSE&outputsize=full&apikey=5L7LU6KQ0CS7U3KI";

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $apiUrl,
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

                $response = [
                    'status' => false,
                    'message' => "cURL Error #:" . $err,
                ];

                return response()->json($response, 200);

                exit;

            }

            $obj = json_decode($response, true);

            if ($symbol=='bse' && isset($obj['Error Message'])) {
                $response = [
                    'status' => false,
                    'message' => $obj['Error Message'],
                ];

                return response()->json($response, 200);

                exit;
            }
        }

        if ($symbol=='NSE' && isset($obj['Error Message'])) {

            $response = [
                'status' => false,
                'message' => $obj['Error Message'],
            ];

            return response()->json($response, 200);

            exit;

        }
        //$obj = json_decode($response);

        $chartArray = [];
        $i = 0;

        if($symbol=='bse' && isset($obj['Time Series (Daily)'])) {
        //if($symbol=='bse') {
            //array
            //echo '<pre>';
            //print_r($obj['Time Series (Daily)']);
            //exit();
            foreach ($obj['Time Series (Daily)'] as $key => $chatRow) {
                $i++;

                //if(strtotime(Carbon::now()->subMonth()->format('Y-m-d'))<=strtotime($key)) {
                if(strtotime($monthMarketData)<=strtotime($key)) {
                    //unset($key);
                    $chartArray[$key]['date'] = $key ?? '';
                    $chartArray[$key]['open'] = $chatRow['1. open'] ?? '';
                    $chartArray[$key]['high'] = $chatRow['2. high'] ?? '';
                    $chartArray[$key]['low'] = $chatRow['3. low'] ?? '';
                    $chartArray[$key]['close'] = $chatRow['4. close'] ?? '';
                    $chartArray[$key]['adjusted_close'] = $chatRow['5. adjusted close'] ?? '';
                    $chartArray[$key]['volume'] = $chatRow['6. volume'] ?? '';
                    //$chartArray[$key]['dividend_amount'] = $chatRow['7. dividend amount'] ?? '';
                    //$chartArray[$key]['split_coefficient'] = $chatRow['8. split coefficient'] ?? '';

                }
            }

            $chartArray = array_values($chartArray);
        }elseif($symbol=='nse'){

            foreach ($obj['historical'] as $key => $chatRow) {
                $i++;
                $chartArray[$key]['date'] = $chatRow['date'] ?? '';
                $chartArray[$key]['open'] = $chatRow['open'] ?? '';
                $chartArray[$key]['high'] = $chatRow['high'] ?? '';
                $chartArray[$key]['low'] = $chatRow['low'] ?? '';
                $chartArray[$key]['close'] = $chatRow['close'] ?? '';
                $chartArray[$key]['adjusted_close'] = $chatRow['adjClose'] ?? '';
                $chartArray[$key]['volume'] = $chatRow['volume'] ?? '';
            }
        }

        //$information = $obj['Meta Data']['1. Information'] ?? '';
        //$symbol = $obj['Meta Data']['2. Symbol'] ?? '';
        //$last_refreshed = $obj['Meta Data']['3. Last Refreshed'] ?? '';
       // $output_size = $obj['Meta Data']['4. Output Size'] ?? '';
        //$time_zone = $obj['Meta Data']['5. Time Zone'] ?? '';
        $status = false;
        $message = 'Data not found.';
        if(count($chartArray)) { $status = true; $message = 'Data found.';};
        $response = [
            'status' => $status,
            'message' => $message,
            //'information' => $information,
            //'symbol' => $symbol,
            //'last_refreshed' => $last_refreshed,
            //'output_size' => $output_size,
            //'time_zone' => $time_zone,
            'data' => $chartArray
        ];

        return response()->json($response, 200);

    }
}
