<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Exception;

class SettingsController extends Controller
{
    	
	public function tollFreeNumber(Request $request)
	{
		//Site settings
		$settings = $this->siteSettings();

		//return $settings;
		$data_arr['Settings']['toll_free_number'] = $settings['toll_free_number'] ?? '';
		
		$response = $data_arr['Settings'];
		
		return response()->json($response, 200);
	}	

	/**
     *
     * Get setting data
     */
    public function settingdata(Request $request)
    {
        try {
            
            
            //Site settings
            $settings = $this->siteSettings();

            //return $settings; https://wa.me/918739955348
            //$data_arr['Settings']['whats_app_number'] = 'https://api.whatsapp.com/send?phone=91'.$settings['whats_app_number'] ?? '';
            $data_arr['Settings']['whats_app_number'] = 'https://wa.me/91'.$settings['whats_app_number'] ?? '';
            /*if(isset($settings['whats_app_number'])) {
            	$data_arr['Settings']['whats_app_number'] = 'https://web.whatsapp.com/send?phone=919509187383&amp;text=Hello';
            }else{
            	$data_arr['Settings']['whats_app_number'] = '';	
            }*/
            
            $data_arr['Settings']['email'] = $settings['email'] ?? '';
            $data_arr['Settings']['youtube_url'] = $settings['youtube_video_link'] ?? '';
            $data_arr['Settings']['facebook'] = $settings['facebook'] ?? '';
            $data_arr['Settings']['twitter'] = $settings['twitter'] ?? '';
            
            $response = $data_arr['Settings'];

        
        } catch (Exception $e) {

           $response = [
                'message' => $e->getMessage(),
            ]; 
        }

        return response()->json($response, 200);
    }

    /**
     * Get site setting detail
     *
     * @return \Illuminate\Http\Response
     */
    public function siteSettings()
    {
        $names = [];
        $values = [];
        
        $settings = Setting::all(['key', 'value'])->toArray();
        
        foreach($settings as $thing)
        {
            $names[] = $thing['key'];
            $values[] = $thing['value'];
        }
        
        return array_combine($names, $values);
    }
}
