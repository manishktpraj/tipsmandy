<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\FcmTrait;

class TestPushNotificationController extends Controller
{
    use FcmTrait;
    
    const DEVICETOKEN = 'e7gZf_H0SW6DL1KkzZkom3:APA91bGN5f_VFVo-W8B9G8sOHdFDAq90FfpUaXfLHeeavUE-Sf8RvwloCE-Y2G3wk8RtvYH95rVSokhkRgD2S2V7K_ts-dTXbJd9bNWUTp7RtGw-pvcPOpLXqMa7hqrM-AnnqgoL_me1';

    public function sendTestNotification(Request $request)
    {

        $this->fcmPushNotification(self::DEVICETOKEN, 'Stock Edge', 'Test Notification.', 'Notification');
    }
}
