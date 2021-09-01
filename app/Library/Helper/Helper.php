<?php

// This class file to define all general functions
namespace App\Library\Helper;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\User;
use App\Admin;
use App\Models\Role;
use App\Models\SitePermission;
use Protocol;

/**
* Helper Class
*/
class Helper
{

    /************ Make Database date readable ************/
    public static function date_ago($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->diffForHumans();
    }

    /*********** Date format IN d/m/Y ***************/
    public static function dateformatDmy($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }

    /*********** Date format string IN M d, Y ***************/
    public static function dateformatmdy($date)
    {
        return Carbon::parse($date)->format('M d, Y');
    }

    /*********** Date format IN d/m/Y ***************/
    public static function date_string($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }

    /*********** Date format ***************/
    public static function dateFormat($date)
    {
        return Carbon::parse($date)->format('M-d-Y');
    }

    /*********** Date format (june 09,2018) ***************/
    public static function dateFormatMdYs($date)
    {
        return Carbon::parse($date)->format('M d,Y');
    }

    /*********** Date format ***************/
    public static function timeFormat($time)
    {
        return Carbon::parse($time)->format('h:i:s A');
    }

    /*********** Time format for order ***************/
    public static function ordertimeFormat($time)
    {
        return Carbon::parse($time)->format('h:i a');
    }

    /*********** Month Date format ***************/
    public static function dateFormatMonth($date)
    {
        return Carbon::parse($date)->format('M');
    }

    /*********** Date format ***************/
    public static function dateformatDate($date)
    {
        return Carbon::parse($date)->format('d');
    }


    /*********** Week format ***************/
    public static function weekFormat($date)
    {
        return Carbon::parse($date)->format('l');
    }

    /*********** Time format ***************/
    public static function formatTime($time)
    {
        return Carbon::parse($time)->format('H:i');
    }

    /**
     * String Date
     */
    public static function dateToFormatted($date)
    {
        return Carbon::parse($date)->toFormattedDateString();
    }


    /*********** Created date format ***************/
    public static function createdformatDate($date)
    {
        return Carbon::parse($date)->format('H:i');
    }

    /**
     * Get status action buttons
     *
     */
    public static function getStatus($current_status, $id, $cls=NULL) {

        $html = '';

        switch($current_status) {

            case '0' :
                $html = '<span class="m-badge m-badge--danger m-badge--wide _badge_status '.$cls.' _btn_status_'.$id.'" data-val="'.$id.'">Inactive</span>';
                break;

            case '1' :
                $html = '<span class="m-badge m-badge--success m-badge--wide _badge_status '.$cls.' _btn_status_'.$id.'" data-val="'.$id.'">Active</span>';
                break;

            default:
            $html = '<span class="m-badge m-badge--danger m-badge--wide">Inactive</span>';
        }

        return $html;
    }

    /**
     * Get all action buttons
     *
     */
    public static function getButtonss($array = []) {

        $btn = [
            'Edit' => '<a href="LINK" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details"><i class="la la-edit"></i></a>',
            'Delete' => '
                <form class="delete-form" action="LINK" method="POST" style="display:inline;">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="_method" value="DELETE">
                    <a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" href="javascript:;"  onclick="confirm_click(this);" title="Delete">
                        <i class="la la-trash"></i>
                    </a>
                </form>'
        ];

        $html = '';
        foreach($array as $arr)
        {
            $html  .= str_replace("LINK",$arr['link'], $btn[$arr['key']]);
        }
        return $html;
    }


    /**
     * Get all action buttons
     *
     */
    public static function getButtons($array = []) {

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
            //$html  .= str_replace("LINK",$arr['link'], $btn[$arr['key']]);
            $html  .= str_replace("LINK",$arr['link'], $btn[$arr['key']]);
        }
        return $html;
    }

    public static function checkPermission($role_id, $manager, $permission)
    {

        $role = Role::where('id', $role_id)->where('name' , '!=', 'Super Admin')->count();

        if($role){

            $permission_count = DB::table('managers')
                             ->join('site_permissions', 'site_permissions.manager_id', '=', 'managers.id')
                             ->where('site_permissions.role_id', $role_id)
                             ->where('managers.slug', $manager)
                             ->where('site_permissions.'.$permission, 1)
                             ->count();

          if($permission_count){

              return true;

          }else{

              return false;
          }

        }

        return true;
    }
}
