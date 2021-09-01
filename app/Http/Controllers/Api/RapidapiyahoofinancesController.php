<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Traits\CurlTrait;
use Carbon\Carbon;
use App\Models\Tip;
use Exception;

class RapidapiyahoofinancesController extends Controller
{
    use CurlTrait;

    const XRAPIDAPIHOST = 'apidojo-yahoo-finance-v1.p.rapidapi.com';
        
    //const XRAPIDAPIKEY = '498b014f77msh41ff55343856325p171f45jsn73c19120e10b';
	
	const XRAPIDAPIKEY = '91a67b3856mshb8f78c73feb951ep1027cdjsn584a8058b4c2';
			
	protected $rapidapitestkey;
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rapidapitestkey = config('settings.rapidapi_test_key');
    }
	
    public function liveMetalPrices(Request $request)
    {
        
        if(request()->ajax()) {

            $output = array('success' => '', 'error' => '', 'data' => '', 'regularMarketPrice' => '');

            try {

                $symbol = $request->symbolVal;
        
                $array['rapidapiHost'] = "live-metal-prices.p.rapidapi.com";
                $array['rapidapiKey'] = self::XRAPIDAPIKEY;
                $array['rapidapiUrl'] = "https://live-metal-prices.p.rapidapi.com/v1/latest/".$symbol."/INR/gram";        

                $curl = $this->rapidapiCurl($array);

                $response = curl_exec($curl);
                
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {

                    $output['error'] = "cURL Error #:" . $err;

                } else {

                    $obj = json_decode($response);
                    
                    if(!empty($obj->validationMessage[0])){

                        $output['error'] = $obj->validationMessage[0];

                    }elseif(!empty($obj->message)) {
                        
                        $output['error'] = $obj->message;
                    
                    }elseif(!empty($obj->rates->$symbol)) {

                        $output['regularMarketPrice'] = number_format($obj->rates->$symbol, 2);
                        $output['success'] = 'Data found successfully.';

                    }else{

                        $output['error'] = 'Api not provide data. please try again!';
                    }
                    
                }

            } catch (Exception $e) {
                
                $output['error'] = $e->getMessage();

            }

            return $this->outputJSON($output);
        
        }else{
            
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
                exit();
                $obj = json_decode($response);
                $symbol = 'XAU';
                echo '<pre>';
                print_r($obj);
                //echo number_format($obj->rates->$symbol, 2);
                //exit(); 
                if(!empty($obj->validationMessage[0])){

                    $output['error'] = $obj->validationMessage[0];

                }elseif(!empty($obj->message)) {
                    
                    $output['error'] = $obj->message;
                
                }elseif(!empty($obj->rates->$symbol)) {

                    $output['regularMarketPrice'] = number_format($obj->rates->$symbol, 2);
                    $output['success'] = 'Data found successfully.';

                }else{

                    $output['error'] = 'Api not provide data. please try again!';
                }
                
            }

            return $this->outputJSON($output);
        }
        
    }




    public function getMarketChartData(Request $request)
    {
        
        $array['rapidapiHost'] = self::XRAPIDAPIHOST;
        $array['rapidapiKey'] = self::XRAPIDAPIKEY;
        $array['rapidapiUrl'] = "https://apidojo-yahoo-finance-v1.p.rapidapi.com/market/get-charts?symbol=TCS.NS&interval=1d&range=3mo&region=INR&comparisons=%5Ebsesn%2C%5Ensei";        

        $curl = $this->rapidapiCurl($array);

        $response = curl_exec($curl);
        
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {

            $response = [
                'status' => false,
                'message' => "cURL Error #:" . $err,            
            ];

            return response()->json($response, 200);

        } else {

            
            $getQuotesArray['rapidapiHost'] = self::XRAPIDAPIHOST;
            $getQuotesArray['rapidapiKey'] = self::XRAPIDAPIKEY;
            $getQuotesArray['rapidapiUrl'] = "https://apidojo-yahoo-finance-v1.p.rapidapi.com/market/v2/get-quotes?region=IN&symbols=%5Ebsesn%2C%5Ensei";

            $getQuotescurl = $this->rapidapiCurl($getQuotesArray);

            $getQuotesresponse = curl_exec($getQuotescurl);
            
            $getQuoteserr = curl_error($getQuotescurl);

            curl_close($getQuotescurl);

            $quoteResponseBse = [];
            $quoteResponseNse = [];

            if ($getQuoteserr) {

                $response = [
                    'status' => false,
                    'message' => "cURL Error #:" . $err,            
                ];

                return response()->json($response, 200);

            } else {

                $objgetQuotes = json_decode($getQuotesresponse);
				
				if(isset($objgetQuotes->message)) {

                    $response = [
                        'status' => false,
                        'message' => $objgetQuotes->message
                    ];

                    return response()->json($response, 200);

                    exit;
                    
                }

                //Bse
                $quoteResponseBse['regularMarketPrice'] = $objgetQuotes->quoteResponse->result[0]->regularMarketPrice ?? '';
                $quoteResponseBse['regularMarketChange'] = $objgetQuotes->quoteResponse->result[0]->regularMarketChange ?? '';
                $quoteResponseBse['regularMarketChangePercent'] = $objgetQuotes->quoteResponse->result[0]->regularMarketChangePercent ?? '';
                $quoteResponseBse['marketState'] = $objgetQuotes->quoteResponse->result[0]->marketState ?? '';
                $quoteResponseBse['fiftyTwoWeekLowChange'] = $objgetQuotes->quoteResponse->result[0]->fiftyTwoWeekLowChange ?? '';
                $quoteResponseBse['fiftyTwoWeekLowChangePercent'] = $objgetQuotes->quoteResponse->result[0]->fiftyTwoWeekLowChangePercent ?? '';
                $quoteResponseBse['fiftyTwoWeekRange'] = $objgetQuotes->quoteResponse->result[0]->fiftyTwoWeekRange ?? '';
                $quoteResponseBse['fiftyTwoWeekHighChange'] = $objgetQuotes->quoteResponse->result[0]->fiftyTwoWeekHighChange ?? '';
                $quoteResponseBse['fiftyTwoWeekHighChangePercent'] = $objgetQuotes->quoteResponse->result[0]->fiftyTwoWeekHighChangePercent ?? '';
                $quoteResponseBsequoteResponseBse['fiftyTwoWeekLow'] = $objgetQuotes->quoteResponse->result[0]->fiftyTwoWeekLow ?? '';
                $quoteResponseBse['fiftyTwoWeekHigh'] = $objgetQuotes->quoteResponse->result[0]->fiftyTwoWeekHigh ?? '';
                
                //Nse
                $quoteResponseNse['regularMarketPrice'] = $objgetQuotes->quoteResponse->result[1]->regularMarketPrice ?? '';
                $quoteResponseNse['regularMarketChange'] = $objgetQuotes->quoteResponse->result[1]->regularMarketChange ?? '';
                $quoteResponseNse['regularMarketChangePercent'] = $objgetQuotes->quoteResponse->result[1]->regularMarketChangePercent ?? '';
                $quoteResponseNse['marketState'] = $objgetQuotes->quoteResponse->result[1]->marketState ?? '';
                $quoteResponseNse['fiftyTwoWeekLowChange'] = $objgetQuotes->quoteResponse->result[1]->fiftyTwoWeekLowChange ?? '';
                $quoteResponseNse['fiftyTwoWeekLowChangePercent'] = $objgetQuotes->quoteResponse->result[1]->fiftyTwoWeekLowChangePercent ?? '';
                $quoteResponseNse['fiftyTwoWeekRange'] = $objgetQuotes->quoteResponse->result[1]->fiftyTwoWeekRange ?? '';
                $quoteResponseNse['fiftyTwoWeekHighChange'] = $objgetQuotes->quoteResponse->result[1]->fiftyTwoWeekHighChange ?? '';
                $quoteResponseNse['fiftyTwoWeekHighChangePercent'] = $objgetQuotes->quoteResponse->result[1]->fiftyTwoWeekHighChangePercent ?? '';
                $quoteResponseBsequoteResponseBse['fiftyTwoWeekLow'] = $objgetQuotes->quoteResponse->result[1]->fiftyTwoWeekLow ?? '';
                $quoteResponseNse['fiftyTwoWeekHigh'] = $objgetQuotes->quoteResponse->result[1]->fiftyTwoWeekHigh ?? '';


            }


			$obj = json_decode($response);
			
			if(isset($obj->message)) {

                $response = [
                    'status' => false,
                    'message' => $obj->message
                ];

                return response()->json($response, 200);

                exit;
                
            }

            $responseStatus = 202;
            
            $apiStatus = false;
            
            $message = 'Data not found.';

            $marketChartData = $obj->chart->result[0]->comparisons;

            if(isset($marketChartData)) {$responseStatus = 200; $message = 'Data found.'; $apiStatus = true;}

            $chartArray = [];
			
			foreach ($marketChartData as $key => $chatRow) {
				
				$highArray = array_filter($chatRow->high); //array_filter() function to remove or filter empty values from an array.
				$chartArray[$key]['symbol'] = $chatRow->symbol;
				$chartArray[$key]['high'] = array_values($highArray); //The array_values() function returns an array containing all the values of an array.
                $lowArray = array_filter($chatRow->low); //array_filter() function to remove or filter empty values from an array.
                $chartArray[$key]['low'] = array_values($lowArray); //The array_values() function returns an array containing all the values of an array.
				$chartArray[$key]['chartPreviousClose'] = $chatRow->chartPreviousClose;
				$chartArray[$key]['close'] = array_slice($chatRow->close, -9);

                $getChartOpenArray = [];
                $openChartPrice = array_slice($chatRow->open, -9);

                foreach(array_slice($chatRow->close, -9) as $closeKey => $closeValue)
                {
                    //if(isset($closeValue) && isset($chatRow->open[$closeKey])) {
                    if(isset($closeValue) && isset($openChartPrice[$closeKey])) {
                        //$closeValueMrp = str_replace('.', '', $closeValue);
                        //$openValueMrp = str_replace('.', '', $openChartPrice[$closeKey]);
                        //$getChartOpenArray[$closeKey] = $closeValue-$chatRow->open[$closeKey];
                        //$getChartOpenArray[$closeKey] = number_format($closeValueMrp-$openValueMrp, 2);
                        // Use substr() and strpos() function to remove
                        // portion of string after certain character
                        if(strstr($closeValue, ".", true)) {
                            $closeValueMrp = strstr($closeValue, ".", true);
                        }else{
                            $closeValueMrp = $closeValue;
                        }
                        if(substr($openChartPrice[$closeKey], 0, strpos($openChartPrice[$closeKey], "."))) {
                            $openValueMrp = substr($openChartPrice[$closeKey], 0, strpos($openChartPrice[$closeKey], "."));
                        }else{
                            $openValueMrp = $openChartPrice[$closeKey];
                        }
                        $getChartOpenArray[$closeKey] = $closeValueMrp-$openValueMrp;
                    }
                }

                $chartArray[$key]['different'] = array_values($getChartOpenArray);
            }

            $timestampArray = [];
            $chartTimestamps = array_slice($obj->chart->result[0]->timestamp, -9);
            foreach ($chartTimestamps as $timestampsKey => $timestampsValue) {
                //$timestampArray[$timestampsKey]['timestamp'] = date('Y-m-d', strtotime($timestampsValue));
                $timestampArray[$timestampsKey]['date'] = date('d', $timestampsValue);
            }
            $response = [
                'status' => $apiStatus,
                'message' => $message,            
                'quoteResponseBse' => $quoteResponseBse,
                'quoteResponseNse' => $quoteResponseNse,
                'timestamp' => $timestampArray,
                'data' => $chartArray
            ];

            return response()->json($response, $responseStatus);
            
            
        }

    }
	
	
	public function getautocompleteData(Request $request)
    {
		
		$validator = Validator::make($request->only(['q', 'region']), [ 
			'q' => 'required|string',
			'region' => 'required|string'
		],[
            'q.required' => 'The query field is required.'
        ]);            

		if ($validator->fails()) {
			
			return $this->outputJSON(['message' => $validator->errors()->first()], $this->notauthorized);
			exit();            
		}
    
        try {
		
    		//request data
            $searchValue = Str::upper($request->q);
            $region = Str::upper($request->region);
            
            $array['rapidapiHost'] = self::XRAPIDAPIHOST;
            //$array['rapidapiKey'] = self::XRAPIDAPIKEY;
            $array['rapidapiKey'] = $this->rapidapitestkey;
            $array['rapidapiUrl'] = "https://apidojo-yahoo-finance-v1.p.rapidapi.com/market/auto-complete?query=".$searchValue."&region=".$region;

            $curl = $this->rapidapiCurl($array);

            $response = curl_exec($curl);
            
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {

                $response = [
                    'status' => false,
                    'message' => "cURL Error #:" . $err,            
                ];

                return response()->json($response, 200);

            } else {

                $obj = json_decode($response);

    			if(isset($obj->message)) {

                    $response = [
                        'status' => false,
                        'message' => $obj->message
                    ];

                    return response()->json($response, 200);

                    exit;
                    
                }

                if(isset($obj->ResultSet->Result) && !empty($obj->ResultSet->Result)) {

                    $responseStatus = 200;
                
                    $apiStatus = true;
                    
                    $message = 'Data found.';

                    $marketChartData = $obj->ResultSet->Result;

                }else{

                    $responseStatus = 200;
                
                    $apiStatus = false;
                    
                    $message = 'Data not found.';

                    $marketChartData = '';
                }
                

                $response = [
                    'status' => $apiStatus,
                    'message' => $message,            
                    'query' => $searchValue,            
                    'data' => $marketChartData
                ];

                return response()->json($response, $responseStatus);
                
                
            }

        } catch (Exception $e) {
            
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }

    }
	
	public function getMarketChartData31052021(Request $request)
    {
        
        $array['rapidapiHost'] = self::XRAPIDAPIHOST;
        $array['rapidapiKey'] = self::XRAPIDAPIKEY;
        $array['rapidapiUrl'] = "https://apidojo-yahoo-finance-v1.p.rapidapi.com/market/get-charts?symbol=TCS.NS&interval=1d&range=3mo&region=INR&comparisons=%5Ebsesn%2C%5Ensei";        

        $curl = $this->rapidapiCurl($array);

        $response = curl_exec($curl);
        
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {

            $response = [
                'status' => false,
                'message' => "cURL Error #:" . $err,            
            ];

            return response()->json($response, 200);

        } else {

            
            $getQuotesArray['rapidapiHost'] = self::XRAPIDAPIHOST;
            $getQuotesArray['rapidapiKey'] = self::XRAPIDAPIKEY;
            $getQuotesArray['rapidapiUrl'] = "https://apidojo-yahoo-finance-v1.p.rapidapi.com/market/v2/get-quotes?region=IN&symbols=%5Ebsesn%2C%5Ensei";

            $getQuotescurl = $this->rapidapiCurl($getQuotesArray);

            $getQuotesresponse = curl_exec($getQuotescurl);
            
            $getQuoteserr = curl_error($getQuotescurl);

            curl_close($getQuotescurl);

            $quoteResponseBse = [];
            $quoteResponseNse = [];

            if ($getQuoteserr) {

                $response = [
                    'status' => false,
                    'message' => "cURL Error #:" . $err,            
                ];

                return response()->json($response, 200);

            } else {

                /*echo '<pre>';
                print_r($getQuotesresponse);
                exit();*/

                $objgetQuotes = json_decode($getQuotesresponse);

                /*echo '<pre>';
                print_r($objgetQuotes);
                exit();

                foreach($objgetQuotes as $getQuotesKey => $getQuotesValue)
                {

                }*/
                //Bse
                $quoteResponseBse['regularMarketPrice'] = $objgetQuotes->quoteResponse->result[0]->regularMarketPrice ?? '';
                $quoteResponseBse['regularMarketChange'] = $objgetQuotes->quoteResponse->result[0]->regularMarketChange ?? '';
                $quoteResponseBse['regularMarketChangePercent'] = $objgetQuotes->quoteResponse->result[0]->regularMarketChangePercent ?? '';
                $quoteResponseBse['marketState'] = $objgetQuotes->quoteResponse->result[0]->marketState ?? '';
                $quoteResponseBse['fiftyTwoWeekLowChange'] = $objgetQuotes->quoteResponse->result[0]->fiftyTwoWeekLowChange ?? '';
                $quoteResponseBse['fiftyTwoWeekLowChangePercent'] = $objgetQuotes->quoteResponse->result[0]->fiftyTwoWeekLowChangePercent ?? '';
                $quoteResponseBse['fiftyTwoWeekRange'] = $objgetQuotes->quoteResponse->result[0]->fiftyTwoWeekRange ?? '';
                $quoteResponseBse['fiftyTwoWeekHighChange'] = $objgetQuotes->quoteResponse->result[0]->fiftyTwoWeekHighChange ?? '';
                $quoteResponseBse['fiftyTwoWeekHighChangePercent'] = $objgetQuotes->quoteResponse->result[0]->fiftyTwoWeekHighChangePercent ?? '';
                $quoteResponseBsequoteResponseBse['fiftyTwoWeekLow'] = $objgetQuotes->quoteResponse->result[0]->fiftyTwoWeekLow ?? '';
                $quoteResponseBse['fiftyTwoWeekHigh'] = $objgetQuotes->quoteResponse->result[0]->fiftyTwoWeekHigh ?? '';
                
                //Nse
                $quoteResponseNse['regularMarketPrice'] = $objgetQuotes->quoteResponse->result[1]->regularMarketPrice ?? '';
                $quoteResponseNse['regularMarketChange'] = $objgetQuotes->quoteResponse->result[1]->regularMarketChange ?? '';
                $quoteResponseNse['regularMarketChangePercent'] = $objgetQuotes->quoteResponse->result[1]->regularMarketChangePercent ?? '';
                $quoteResponseNse['marketState'] = $objgetQuotes->quoteResponse->result[1]->marketState ?? '';
                $quoteResponseNse['fiftyTwoWeekLowChange'] = $objgetQuotes->quoteResponse->result[1]->fiftyTwoWeekLowChange ?? '';
                $quoteResponseNse['fiftyTwoWeekLowChangePercent'] = $objgetQuotes->quoteResponse->result[1]->fiftyTwoWeekLowChangePercent ?? '';
                $quoteResponseNse['fiftyTwoWeekRange'] = $objgetQuotes->quoteResponse->result[1]->fiftyTwoWeekRange ?? '';
                $quoteResponseNse['fiftyTwoWeekHighChange'] = $objgetQuotes->quoteResponse->result[1]->fiftyTwoWeekHighChange ?? '';
                $quoteResponseNse['fiftyTwoWeekHighChangePercent'] = $objgetQuotes->quoteResponse->result[1]->fiftyTwoWeekHighChangePercent ?? '';
                $quoteResponseBsequoteResponseBse['fiftyTwoWeekLow'] = $objgetQuotes->quoteResponse->result[1]->fiftyTwoWeekLow ?? '';
                $quoteResponseNse['fiftyTwoWeekHigh'] = $objgetQuotes->quoteResponse->result[1]->fiftyTwoWeekHigh ?? '';

                //echo '<pre>';
                //print_r($quoteResponse);
                //exit();
            }

            $obj = json_decode($response);

            $responseStatus = 202;
            
            $apiStatus = false;
            
            $message = 'Data not found.';

            $marketChartData = $obj->chart->result[0]->comparisons;

            if(isset($marketChartData)) {$responseStatus = 200; $message = 'Data found.'; $apiStatus = true;}

            $chartArray = [];

            foreach ($marketChartData as $key => $chatRow) {
                
                $chartArray[$key] = $chatRow;
                
            }

            $response = [
                'status' => $apiStatus,
                'message' => $message,            
                'quoteResponseBse' => $quoteResponseBse,            
                'quoteResponseNse' => $quoteResponseNse,            
                'data' => $chartArray
            ];

            return response()->json($response, $responseStatus);
            
            
        }

    }


    public function updategetQuotestData(Request $request)
    {
        //api url : https://apidojo-yahoo-finance-v1.p.rapidapi.com/market/v2/get-quotes?region=IN&symbols=TCS.NS%2CNTPC.NS%2CRELIANCE.NS%2CTCS.BO%2CINFO.BO
        //symbols : TCS.NS,NTPC.NS,RELIANCE.NS,TCS.BO,INFO.BO
        //region : IN

        try {

            $array['rapidapiHost'] = self::XRAPIDAPIHOST;
            $array['rapidapiKey'] = $this->rapidapitestkey;
            $array['rapidapiUrl'] = "https://apidojo-yahoo-finance-v1.p.rapidapi.com/market/v2/get-quotes?region=IN&symbols=TCS.NS%2CNTPC.NS%2CRELIANCE.NS%2CTCS.BO%2CINFO.BO";

            $curl = $this->rapidapiCurl($array);

            $response = curl_exec($curl);

            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {

                $response = [
                    'status' => false,
                    'message' => "cURL Error #:" . $err,
                ];

                return response()->json($response, 200);

            } else {

                $obj = json_decode($response);

                if(isset($obj->message)) {

                    $response = [
                        'status' => false,
                        'message' => $obj->message
                    ];

                    return response()->json($response, 200);

                    exit;

                }

                if(isset($obj->quoteResponse->result)) {

                    foreach($obj->quoteResponse->result as $key => $getQuotesRow)
                    {
                        if(!empty($getQuotesRow->symbol)) {

                            $getAllTips = Tip::whereNotNull('symbols')->select('symbols')->get();

                            foreach($getAllTips as $rowTip)
                            {
                                if($getQuotesRow->symbol==$rowTip->symbols) {
                                    Tip::where('symbols', $getQuotesRow->symbol)->update([
                                        'price' => $getQuotesRow->regularMarketPrice
                                    ]);
                                }
                            }

                            echo "\$symbol : " .$getQuotesRow->symbol .' regularMarketPrice updated successfully'.'<br>';
                        }

                    }
                }
            }

        } catch (Exception $e) {

            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }

    }
}
