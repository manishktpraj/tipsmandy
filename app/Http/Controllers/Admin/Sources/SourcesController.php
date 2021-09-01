<?php

namespace App\Http\Controllers\Admin\Sources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Traits\Admin\FormButtons;
use App\Traits\Admin\AuthorizationsTrait;
use Exception;
use Helper;
use App\Http\Requests\Admin\Tips\StoreTip;
use App\Models\Source;

class SourcesController extends Controller
{

    use FormButtons, AuthorizationsTrait;

    protected $sourceTbl;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');

        $this->sourceTbl = new Source;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-source', 'is_read'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Source', 'Manage Source');

        $renderButtons = $this->addButtons(route('admin.sources.create'), 'manage-sources');

        $sources = $this->sourceTbl::latest()->get(['id', 'name']);

        return view('admin.sources.index', compact('renderButtons', 'sources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-source', 'is_add'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Source', 'Create Source');

        $data['renderButtons'] = $this->addFormButtons(route('admin.sources.index'));

        $data['backButtons'] = $this->backButtons(route('admin.sources.index'));

        return view('admin.sources.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-source', 'is_add'))
        {
            return abort(403);
        }

        //Validation request data
        $request->validate([
            'name' => 'required|string|max:191|unique:sources',
        ]);

        $create = Source::create(['name' => $request->name]);

        if($create) {

            return redirect()->route('admin.sources.index')->with('success', 'Source created successfully.');
        }

        return redirect()->back()->with('error', 'Oops. Something went wrong. Please try again.');
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
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-source', 'is_edit'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Source', 'Edit Source');

        $data['source'] = $this->sourceFindById($id);
        
        $data['renderButtons'] = $this->editFormButtons(route('admin.sources.index'));

        $data['backButtons'] = $this->backButtons(route('admin.sources.index'));

        return view('admin.sources.edit', $data);

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
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-source', 'is_edit'))
        {
            return abort(403);
        }

        $source = $this->sourceFindById($id);

        //Validation request data
        $request->validate([
            'name' => 'required|string|max:191|unique:sources,name,'.$id,
        ]);


        $source->update(['name' => $request->name]);

        return redirect()->route('admin.sources.index')->with('success', 'Source created successfully.');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-source', 'is_delete'))
        {
            return redirect()->route('admin.dashboard')->with('warning', 'You are not authorised for this action.');
        }
        
        $source = $this->sourceFindById($id);
        $source->delete();

        return redirect()->route('admin.sources.index')->with('success', 'Source deleted successfully.');
    }

    /**
     *
     * Source detail find by id.
     */
    public function sourceFindById($id)
    {
        return  $this->sourceTbl::findorFail($id);
    }
}
