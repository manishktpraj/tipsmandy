<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use App\Models\Setting;
use App\Traits\UploadAble;
use App\Traits\Admin\AuthorizationsTrait;

class SettingsController extends Controller
{

    use UploadAble, AuthorizationsTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'settings', 'is_read'))
        {
            return abort(403);
        }

        $this->setPageTitle('Settings', 'Manage Settings');

        return view('admin.settings.settings');
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
    public function update(Request $request)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'settings', 'is_edit'))
        {
            return abort(403);
        }

        //Validation request data
        $request->validate([
            'whats_app_number' => 'required|numeric',
            'youtube_thumbnail' => 'nullable|image|mimes:jpeg,jpg,png'
        ],[
            //'whats_app_number.max' => 'Whatsapp no is invalid.',
            //'whats_app_number.min' => 'Whatsapp no is invalid.',
            //'whats_app_number.numeric' => 'Whatsapp no is invalid.',
        ]);

        if ($request->has('youtube_thumbnail') && ($request->file('youtube_thumbnail') instanceof UploadedFile)) {

            if (config('settings.youtube_thumbnail') != null) {
                $this->deleteOne(config('settings.youtube_thumbnail'));
            }
            $logo = $this->uploadOne($request->file('youtube_thumbnail'), 'img');
            Setting::set('youtube_thumbnail', $logo);


            $keys = $request->except('_method', '_token', 'youtube_thumbnail');

            foreach ($keys as $key => $value)
            {
                Setting::set($key, $value);
            }

        } else {

            $keys = $request->except('_method', '_token');

            foreach ($keys as $key => $value)
            {
                Setting::set($key, $value);
            }
        }
        
        return redirect()->back()->with('success', 'Settings updated successfully.');
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
