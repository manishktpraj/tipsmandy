<?php

namespace App\Http\Controllers\Api\Videos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\LearnVideo;
use App\Models\VideoCategory;
use App\Models\StockEdgeVideo;
use Exception;
use Helper;

class LearnVideosController extends Controller
{
	
	protected $tbl, $videoCategoryTbl;

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tbl = new LearnVideo;
        $this->videoCategoryTbl = new VideoCategory;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //$videos = $this->tbl::latest()->get(['id', 'title', 'sub_title', 'video_url']);
        
        $videos = $this->videoCategoryTbl::latest()->get(['id', 'title']);
		
		$videos = $this->videoCategoryTbl::
				join('learn_videos', 'video_categories.id', '=', 'learn_videos.title')
				->select('video_categories.*', 'learn_videos.sub_title', 'learn_videos.content', 'learn_videos.icon')
				->groupBy('video_categories.title')
				->get();
        $videosArray = [];        

        $responseStatus = 202;
        
        $apiStatus = false;
        
        $message = 'Videos not found.';

        if(count($videos)) {$responseStatus = 200; $message = 'Videos lists.'; $apiStatus = true;}


        foreach ($videos as $key => $video) {

            //$videosArray[$key]['video_id'] = $video->id;
            $videosArray[$key]['title'] = $video->title;
            //$videosArray[$key]['title'] = $video->videoTitle->title;

            //$videossubTitleArray = [];

            //$videosArray[$key]['video_sub_title'] = $video->sub_title;

            $videossubTitleArray = [];

            $image_path = public_path('uploads/icons');

            foreach ($video->learnVideos as $learnVideosKey => $learnVideo) {
                $videossubTitleArray[$learnVideosKey]['video_id'] = $learnVideo->id;
                $videossubTitleArray[$learnVideosKey]['sub_title'] = $learnVideo->sub_title;
				$videossubTitleArray[$learnVideosKey]['video_url'] = $learnVideo->video_url;
                
                if(!empty($learnVideo->icon) && File::exists($image_path.'/'.$learnVideo->icon)) {

                    $videossubTitleArray[$learnVideosKey]['thumbnail '] = url('public/uploads/icons/'.$learnVideo->icon);
                }else{
                    $videossubTitleArray[$learnVideosKey]['thumbnail '] = '';
                }
                $videossubTitleArray[$learnVideosKey]['descrition'] = htmlspecialchars(strip_tags($learnVideo->content));
            }

            $videosArray[$key]['subTitles'] = $videossubTitleArray;

        }


        $response = [
                'status' => $apiStatus,
                'message' => $message,            
                'data' => $videosArray
            ];

        return response()->json($response, $responseStatus);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $validator = Validator::make($request->only(['video_id']), [ 
            'video_id' => 'required|string|exists:learn_videos,id'
        ]);            

        if ($validator->fails()) {
            
            return $this->outputJSON(['message' => $validator->errors()->first()], $this->notauthorized);
            exit();            
        }

        $video = $this->tbl::where('id', $request->video_id)->first(['id', 'title', 'sub_title', 'video_url', 'content']);

        if($video) {

        	$array['video_id'] = $video->id;
        	$array['video_title'] = $video->videoTitle->title;
        	$array['video_sub_title'] = $video->sub_title;
        	$array['video_url'] = $video->video_url;
        	$array['content'] = htmlspecialchars(strip_tags($video->content));

        	$response = [
                'status' => true,
                'message' => 'Video detail.',            
                'data' => $array
            ];

        	return response()->json($response, 200);

        }else {

        	$response = [
                'status' => false,
                'message' => 'Video detail no found.',            
                'data' => ''
            ];

        	return response()->json($response, 404);

        }
        
        
    }
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stockedgevideos()
    {

        
		$videos = StockEdgeVideo::latest()->get();
		
        $videosArray = [];        

        $responseStatus = 202;
        
        $apiStatus = false;
        
        $message = 'Videos not found.';

        if(count($videos)) {$responseStatus = 200; $message = 'Videos lists.'; $apiStatus = true;}


        $image_path = public_path('uploads/stockedgevideos');

        foreach ($videos as $key => $video) {

            $videosArray[$key]['title'] = $video->title;
            $videosArray[$key]['video_url'] = $video->youtube_url;
            if(!empty($video->youtube_thumbnail) && File::exists($image_path.'/'.$video->youtube_thumbnail)) {

				$videosArray[$key]['thumbnail '] = url('public/uploads/stockedgevideos/'.$video->youtube_thumbnail);
			}else{
				$videosArray[$key]['thumbnail '] = '';
			}            

        }


        $response = [
                'status' => $apiStatus,
                'message' => $message,            
                'data' => $videosArray
            ];

        return response()->json($response, $responseStatus);
    }
}
