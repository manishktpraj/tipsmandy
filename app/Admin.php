<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\AdminResetPasswordNotification as Notification;

class Admin extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'email', 'password', 'phone_no', 'is_role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Custom password reset notification.
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new Notification($token));
    }


    /**
     * Get the role.
     */
    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'is_role')->withDefault();
    }

    /**
     * Search staff members.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchmembers($query, $search)
    {
        if(!empty($search)) {

            return $query->where('id','LIKE',"%{$search}%")
                        ->orWhere('name','LIKE',"%{$search}%")
                        ->orWhere('email','LIKE',"%{$search}%")
                        ->orWhere('phone_no','LIKE',"%{$search}%");
        }
    }

    /**
     * Search staff role.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStaffrole($query, $role)
    {
        if(!empty($role)) {
            return $query->where('is_role', $role);
        }
    }
}
