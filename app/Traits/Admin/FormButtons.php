<?php

namespace App\Traits\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\Admin\AuthorizationsTrait;

trait FormButtons
{
    use AuthorizationsTrait;

    public function addFormButtons($routeName)
    {
        return '<button type="submit" class="btn btn-success">Save</button>
                            <a href="'.$routeName.'" class="btn btn-secondary">Cancel</a>';
    }

    public function editFormButtons($routeName)
    {
        return '<button type="submit" class="btn btn-success">Update</button>
                            <a href="'.$routeName.'" class="btn btn-secondary">Cancel</a>';
    }


    public function backButtons($routeName)
    {
        return '<a href="'.$routeName.'" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                            <span>
                                <i class="la la-reply"></i>
                                <span>Back</span>
                            </span>
                        </a>';
    }

    public function addButtons($routeName, $manager, $text = 'Add')
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, $manager, 'is_add'))
        {
            return '';
        }

        return '<a href="'.$routeName.'" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                            <span>
                                <i class="la la-plus"></i>
                                <span>'.$text.'</span>
                            </span>
                        </a>';
    }

    /**
     * Get all action buttons
     *
     */
    public function actionsButtons($array = [])
    {

        $btn = [
            'Edit' => '<a href="LINK" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details"><i class="la la-edit"></i></a>',
            'Delete' => '
                <form class="delete-form" action="LINK" method="POST" style="display:inline;">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="_method" value="DELETE">
                    <a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" href="javascript:;"  onclick="confirm_click(this);" title="Delete">
                        <i class="la la-trash"></i>
                    </a>
                </form>',
            'View' => '<a href="LINK" class="m-portlet__nav-link btn m-btn m-btn--hover-focus m-btn--icon m-btn--icon-only m-btn--pill" title="View Detail"><i class="la  la-eye"></i></a>'
        ];

        $html = '';

        foreach($array as $arr)
        {
            $html  .= str_replace("LINK", $arr['link'], $btn[$arr['key']]);
        }

        return $html;
    }
}
